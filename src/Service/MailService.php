<?php

namespace App\Service;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class MailService
{
    /**
     * @var MailerInterface
     */
    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendEmail(
        string $from,
        string $subject,
        string $htmlTemplate,
        array $context,
        string $to = 'fahimlaidi@gmail.com'
    ): void {

        // var_dump($context);
        // var_dump($subject);
        // var_dump($from);
        // var_dump($to);
        // die;
        $email = (new TemplatedEmail())
            ->from('jugurthaakli@gmail.com')
            ->to('fahimlaidi@gmail.com')
            ->subject($subject)
            ->htmlTemplate($htmlTemplate)
            ->context($context);

        $this->mailer->send($email);
    }
}