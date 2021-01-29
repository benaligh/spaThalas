<?php

namespace App\Controller;

use App\Entity\Appointment;
use App\Form\AppointmentType;
use App\Repository\AppointmentRepository;
use App\Service\AppointmentsNotification;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AppointmentController extends AbstractController
{

    /**
     * @Route("/appointment", name="appointment_view")
     */
    public function index()
    {
        $appointment = new Appointment();
        $form = $this->createForm(AppointmentType::class, $appointment,
            [
                'action' => $this->generateUrl('appointment_add')
            ]
        );

        return $this->render('appointment/add.html.twig', [
            'form' => $form->createView()
        ]);
    }


    /**
     * @Route("/add", name="appointment_add")
     */
    public function addAppointment(Request $request, EntityManagerInterface $em, AppointmentsNotification $notification)
    {
        $appointment = new Appointment();
        $form = $this->createForm(AppointmentType::class, $appointment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($appointment);
            $em->flush();
            $notification->notify($appointment);
            $this->addFlash('success', 'Rendez-vous envoyé avec succès');
        }

        return $this->forward('App\Controller\AppointmentController::index');
    }


    /**
     * @Route("/admin" , name="admin.appointments.index")
     */
    public function listAppointment(AppointmentRepository $repository,Request $request, PaginatorInterface $paginator)
    {
        $appointments = $paginator->paginate($repository->findAll(), $request->query->getInt('page', 1), 3);
        return $this->render('Admin/Appointment/list.html.twig', compact('appointments'));

    }


    /**
     * @Route("/admin/valid/{id}", name="appointment_valid")
     */

    public function validAppointment(Request $request, Appointment $appointment, AppointmentRepository $repository, AppointmentsNotification $notification)
    {
        $session = $request->getSession();
        $appointment = $repository->findOneBy(array('id' => $appointment));

        $appointment->setStatus(1);
        $em = $this->getDoctrine()->getManager();
        $em->persist($appointment);
        $em->flush();
        $notification->notifyValidAppointment($appointment);
        $session->getFlashBag()->add('success', 'Rendez-vous a été validé avec succés');
        return $this->forward('App\Controller\AppointmentController::listAppointment');

    }


}
