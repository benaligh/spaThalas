<?php

namespace App\Service;


use App\Entity\Appointment;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class AppointmentsNotification
{

    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var Environment
     */
    private $twig;

    public function __construct(\Swift_Mailer $mailer, Environment $twig)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;

    }


    public function notify(Appointment $appointment)
    {
        $message = (new \Swift_Message('Rendez-vous Spa: ' . $appointment->getFirstname()))
            ->setFrom('noreply@spa&thalasso.fr')
            ->setTo('contact@spa&thalasso.fr')
            ->setReplyTo($appointment->getEmail())
            ->setBody($this->twig->render('emails/appointment.html.twig', [
                'appointment' => $appointment]), 'text/html');
        $this->mailer->send($message);
    }


    public function notifyValidAppointment(Appointment $appointment)
    {
        $message = (new \Swift_Message('Bon de commande: ' . $appointment->getFirstname()))
            ->setFrom('contact@spa&thalasso.fr')
            ->setTo($appointment->getEmail())
            ->setReplyTo($appointment->getEmail())
            ->setBody($this->twig->render('emails/bonCommande.html.twig', [
                'appointment' => $appointment]), 'text/html');
        $this->mailer->send($message);

        /*$snappy = $this->get("knp_snappy.pdf");
         $html = $this->renderView("emails/bonCommande.html.twig", [
            "title" => "Bon de commande"
        ]);

        $filename = " Bon de Commande Spa";
        return new Response($snappy->getOutputFromHtml($html), 200,
            [
                'Content-Type' => 'application/pdf',
                'content-Disposition' => 'inline; filname="' . $filename . '.pdf"'
            ]
        );*/

    }

}