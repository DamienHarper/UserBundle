<?php

namespace DH\UserBundle\Controller;

use DH\UserBundle\Exception\PasswordResetRequiredException;
use DH\UserBundle\Form\Type\LoginType;
use RuntimeException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="dh_userbundle_login", methods={"GET", "POST"})
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $lastError = $authenticationUtils->getLastAuthenticationError();
        if (null !== $lastError) {
            if ($lastError instanceof PasswordResetRequiredException) {
                // password reset required: we forward the user to the reset password form
                return $this->forward(
                    'DH\UserBundle\Controller\PasswordController::resetAction',
                    ['token' => $lastError->getResetToken()]
                );
            }

            $this->get('session')->getFlashBag()->add(
                'error',
                $this->get('translator')->trans($lastError->getMessageKey(), $lastError->getMessageData(), 'UserBundle')
            );
        }

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        $form = $this->createForm(LoginType::class);
        $form->get('_username')->setData($lastUsername);

        return $this->render('@DHUser/Security/login.html.twig', [
            'form' => $form->createView(),
            'last_username' => $lastUsername,
            'last_error' => $lastError,
        ]);
    }

    /**
     * @Route("/logout", name="dh_userbundle_logout")
     *
     * @throws RuntimeException
     */
    public function logout(): void
    {
        throw new RuntimeException('This should never be reached!');
    }
}
