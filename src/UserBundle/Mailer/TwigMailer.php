<?php

namespace DH\UserBundle\Mailer;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Twig\Environment;

class TwigMailer
{
    /**
     * @var MailerInterface
     */
    protected $mailer;

    /**
     * @var UrlGeneratorInterface
     */
    protected $router;

    /**
     * @var Environment
     */
    protected $twig;

    /**
     * @var array
     */
    protected $parameters;

    public function __construct(MailerInterface $mailer, UrlGeneratorInterface $router, Environment $twig, array $parameters)
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

        $email = (new Email())
            ->from(new Address($fromEmail))
            ->to($toEmail)
            ->subject($subject)
            ->text($textBody)
            ->html($htmlBody)
        ;

        $this->mailer->send($email);
    }
}
