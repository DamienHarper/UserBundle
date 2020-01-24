<?php

namespace DH\UserBundle\Controller;

use DateTimeImmutable;
use DH\UserBundle\Event\PasswordRequestEvent;
use DH\UserBundle\Event\PasswordResetEvent;
use DH\UserBundle\Form\Type\PasswordRequestType;
use DH\UserBundle\Form\Type\PasswordResetType;
use DH\UserBundle\Security\TokenGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;

class PasswordController extends AbstractController
{
    /**
     * Displays password reset request form.
     *
     * @Route("/lost-password", name="dh_userbundle_password_request", methods={"GET"})
     */
    public function requestAction(): Response
    {
        $form = $this->createForm(PasswordRequestType::class);

        return $this->render('@DHUser/Password/request.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Sends password reset request email.
     *
     * @Route("/lost-password", name="dh_userbundle_password_request_send", methods={"POST"})
     */
    public function sendAction(Request $request): Response
    {
        $form = $this->createForm(PasswordRequestType::class);
        $form->handleRequest($request);

        try {
            if ($form->isValid()) {
                $username = $form->getData()['username'];

                if (null === $username) {
                    return $this->render('@DHUser/Password/request.html.twig', [
                        'form' => $form->createView(),
                    ]);
                }

                $user = $this->get('dh_userbundle.user_provider')->findUserByUsername($username);
            } else {
                return $this->render('@DHUser/Password/request.html.twig', [
                    'form' => $form->createView(),
                ]);
            }
        } catch (UsernameNotFoundException $e) {
            $user = null;
        }

        if (null !== $user) {
            $ttl = $this->container->getParameter('dh_userbundle.password_reset.token_ttl');
            if (!$user->isPasswordRequestExpired($ttl)) {
                return $this->render('@DHUser/Password/already_requested.html.twig', [
                    'email' => $user->getEmail(),
                    'ttl' => $ttl / 60 / 60,
                ]);
            }

            if (null === $user->getResetToken()) {
                $user->setResetToken(TokenGenerator::generateToken());
            }

            $mailer = $this->get('dh_userbundle.mailer');
            $mailer->sendResetMessage($user);

            $user->setPasswordRequestedAt(new DateTimeImmutable());
            $this->getDoctrine()->getManager()->persist($user);
            $this->getDoctrine()->getManager()->flush();
        }

        /** @var EventDispatcher $dispatcher */
        $dispatcher = $this->get('event_dispatcher');
        if (null !== $dispatcher) {
            $event = new PasswordRequestEvent($user, [
                'ip' => $request->getClientIp(),
                'referer' => $request->headers->get('referer'),
                'user-agent' => $request->headers->get('User-Agent'),
            ]);
            $dispatcher->dispatch($event, 'security.password.requested');
        }

        return new RedirectResponse($this->get('router')->generate(
            'dh_userbundle_password_request_sent',
            ['email' => null === $user ? null : $user->getEmail()]
        ));
    }

    /**
     * Tells the user to check his email provider.
     *
     * @Route("/lost-password-confirmation", name="dh_userbundle_password_request_sent", methods={"GET"})
     */
    public function sentAction(Request $request): Response
    {
        $email = $request->query->get('email');
        $ttl = $this->container->getParameter('dh_userbundle.password_reset.token_ttl');

        return $this->render('@DHUser/Password/requested.html.twig', [
            'email' => $email,
            'ttl' => $ttl / 60 / 60,
        ]);
    }

    /**
     * Resets user password.
     *
     * @Route("/password-reset/{token}", name="dh_userbundle_password_reset", methods={"GET", "POST"})
     */
    public function resetAction(Request $request, ?string $token): Response
    {
        try {
            $user = $this->get('dh_userbundle.user_provider')->findUserByResetToken($token);
        } catch (UsernameNotFoundException $e) {
            $this->get('session')->getFlashBag()->add(
                'error',
                $this->get('translator')->trans('password.reset.invalid_token', [], 'UserBundle')
            );

            $form = $this->createForm(PasswordRequestType::class);

            return $this->render('@DHUser/Password/request.html.twig', [
                'form' => $form->createView(),
            ]);
        }

        $form = $this->createForm(PasswordResetType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();

            if (null === $user->getPlainPassword()) {
                return $this->render('@DHUser/Password/reset.html.twig', [
                    'form' => $form->createView(),
                    'token' => $token,
                ]);
            }

            if ($password = ('' !== $user->getPlainPassword())) {
                $encoder = $this->get('dh_userbundle.user_provider')->getEncoder($user);
                $user->setPassword($encoder->encodePassword($password, $user->getSalt()));
                $user->eraseCredentials();
            }

            $user->setResetToken(null);
            $user->setPasswordRequestedAt(null);

            if (method_exists($user, 'setIsPasswordResetRequired')) {
                $user->setIsPasswordResetRequired(0);
            }

            /** @var EventDispatcher $dispatcher */
            $dispatcher = $this->get('event_dispatcher');
            if (null !== $dispatcher) {
                $event = new PasswordResetEvent($user, [
                    'ip' => $request->getClientIp(),
                    'referer' => $request->headers->get('referer'),
                    'user-agent' => $request->headers->get('User-Agent'),
                ]);
                $dispatcher->dispatch($event, 'user.password.reset');
            }

            $this->getDoctrine()->getManager()->persist($user);
            $this->getDoctrine()->getManager()->flush();

            $this->get('session')->getFlashBag()->add(
                'success',
                $this->get('translator')->trans('password.reset.success', [], 'UserBundle')
            );

            $url = $this->get('router')->generate('dh_userbundle_login');

            return new RedirectResponse($url);
        }

        return $this->render('@DHUser/Password/reset.html.twig', [
            'token' => $token,
            'form' => $form->createView(),
        ]);
    }
}
