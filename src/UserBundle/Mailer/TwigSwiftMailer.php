<?php

namespace DH\UserBundle\Mailer;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class TwigSwiftMailer
{
    protected $mailer;
    protected $router;
    protected $twig;
    protected $parameters;

    public function __construct(\Swift_Mailer $mailer, UrlGeneratorInterface $router, \Twig_Environment $twig, array $parameters)
    {
        $this->mailer = $mailer;
        $this->router = $router;
        $this->twig = $twig;
        $this->parameters = $parameters;
    }

    public function sendResetMessage(UserInterface $user)
    {
        $template = '@DHUser/Password/request_email.html.twig';
        $url = $this->router->generate(
            'dh_userbundle_password_reset',
            ['token' => $user->getResetToken()],
            UrlGeneratorInterface::ABSOLUTE_URL
        );
        $context = [
            'user' => $user,
            'confirmationUrl' => $url,
        ];

        $this->sendMessage($template, $context, $this->parameters['email_from'], $user->getEmail());
    }

    /**
     * @param string $templateName
     * @param array  $context
     * @param string $fromEmail
     * @param string $toEmail
     */
    protected function sendMessage($templateName, $context, $fromEmail, $toEmail)
    {
        $context = $this->twig->mergeGlobals($context);
        $template = $this->twig->loadTemplate($templateName);
        $subject = $template->renderBlock('subject', $context);
        $textBody = $template->renderBlock('body_text', $context);
        $htmlBody = $template->renderBlock('body_html', $context);

        $message = (new \Swift_Message())
            ->setSubject($subject)
            ->setFrom($fromEmail)
            ->setTo($toEmail)
        ;

        if (!empty($htmlBody)) {
            $message
                ->setBody($htmlBody, 'text/html')
                ->addPart($textBody, 'text/plain')
            ;
        } else {
            $message->setBody($textBody);
        }

        $this->mailer->send($message);
    }
}
