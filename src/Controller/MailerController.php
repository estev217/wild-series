<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class MailerController extends AbstractController
{
    /**
     * @Route("/email")
     */
    public function sendEmail(MailerInterface $mailer)
    {
        $email = (new Email())
            ->from($this->getParameter('mailer_from'))
            ->to('estevao.fernandes217@gmail.com')
            ->subject('Une nouvelle série vient d\'être publiée !')
            ->html("<html>
            <body>
            <p>Hey<br>
            Hey! Learn the best practices of building HTML emails and play with ready-to-go templates.</p>
            <p>Some text.</p>
            </body>
            </html>");
//….
  $mailer->send($email);

        // …
      return new Response(
          'Email was sent'
      );
    }
}
