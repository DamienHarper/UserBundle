<?php

namespace DH\UserBundle\Mailer;

use Swift_Mailer;
use Swift_Message;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Twig\Environment;

class TwigSwiftMailer
{
    protected $mailer;
    protected $router;
    protected $twig;
    protected $parameters;

    public function __construct(Swift_Mailer $mailer, UrlGeneratorInterface $router, Environment $twig, array $parameters)
    {
        $this->mailer = $mailer;
        $this->router = $router;
        $this->twig = $twig;
        $this->parameters = $parameters;
    }

    public function sendResetMessage(UserInterface $user): void
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

    protected function sendMessage(string $templateName, array $context, string $fromEmail, string $toEmail): void
    {
        $context = $this->twig->mergeGlobals($context);
        $template = $this->twig->load($templateName);
        $subject = $template->renderBlock('subject', $context);
        $textBody = $template->renderBlock('body_text', $context);
        $htmlBody = $template->renderBlock('body_html', $context);

        $message = (new Swift_Message())
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
