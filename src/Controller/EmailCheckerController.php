<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class EmailCheckerController extends AbstractController
{
    /**
     * @Route("admin/email-checker", name="app_email_checker")
     */
    public function index(MailerInterface $mailer): Response
    {

        $email = (new Email())
            ->from('hello@example.com')
            ->to('you@mtb-spo.ru')
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('Time for Symfony Mailer!')
            ->text('Sending emails is fun again!')
            ->html('<p>See Twig integration for better HTML integration!</p>');

        $mailer->send($email);

        return $this->render('admin/email_checker/index.html.twig', [
            'controller_name' => 'EmailCheckerController',
        ]);
    }
}
