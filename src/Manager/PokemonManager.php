<?php

namespace App\Manager;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class PokemonManager
{
    protected $mailer;
    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendMail(string $texto)
    {
        $email = (new Email())
            ->from('moycarretero@gmail.com')
            ->to('juliocesarbenitez0391@gmail.com')
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('Esto es ha funcionado')
            ->text($texto)
            ->html("<p>$texto</p>");

        $this->mailer->send($email);
    }
}
