<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Form\ReservationType;
use App\Repository\ReservationRepository;
use App\Service\MailService;
use PhpParser\Node\Stmt\ElseIf_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/reservation')]
class ReservationController extends AbstractController
{

    public function __construct()
    {
        $_SESSION['truc'] = 1;
    }

    #[Route('/', name: 'app_reservation_index', methods: ['GET'])]
    public function index(ReservationRepository $reservationRepository): Response
    {
        return $this->render('reservation/index.html.twig', [
            'reservations' => $reservationRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_reservation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ReservationRepository $reservationRepository, MailService $mailService): Response
    {
        $reservation = new Reservation();
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reservation->setEmpruntDate(new \DateTime());
            $reservation->setIsRendered(false);
            $quantity = $reservation->getMaterial()->getQuantity() -1;
            $reservation->getMaterial()->setQuantity($quantity) ;
            $reservationRepository->save($reservation, true);

            $destinaire = $reservation->getEmail();
            $messageSubject = "Mail de confirmation emprunt";
            $messageBody = "
            <h1>Mail de confirmation emprunt</h1>
            <p>Mail de confirmation emprunt</p>
            ";

            $mailService->sendMail($destinaire, $messageSubject, $messageBody);



            return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reservation/new.html.twig', [
            'reservation' => $reservation,
            'form' => $form,
        ]);
    }




    #[Route('/{id}', name: 'app_reservation_show', methods: ['GET'])]
    public function show(Reservation $reservation): Response
    {
        return $this->render('reservation/show.html.twig', [
            'reservation' => $reservation,
        ]);
    }





    #[Route('/{id}/edit', name: 'app_reservation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reservation $reservation, ReservationRepository $reservationRepository): Response
    {
        $lastreservation = $reservationRepository->find($reservation->getId());
        $ancienbool = $lastreservation->isIsRendered();


        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);


      
        $quantity = $lastreservation->getMaterial()->getQuantity();

        if ($form->isSubmitted() && $form->isValid()) {


            if($ancienbool != $reservation->isIsRendered())
            {
               if($reservation->isIsRendered() == true )
               {
                    $reservation->getMaterial()->setQuantity($quantity + 1);
                } else {
                    $reservation->getMaterial()->setQuantity($quantity - 1);
               }
            }
       


            $reservationRepository->save($reservation, true);

            return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reservation/edit.html.twig', [
            'reservation' => $reservation,
            'form' => $form,
        ]);
    }





    #[Route('/{id}', name: 'app_reservation_delete', methods: ['POST'])]
    public function delete(Request $request, Reservation $reservation, ReservationRepository $reservationRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reservation->getId(), $request->request->get('_token'))) {
            $reservationRepository->remove($reservation, true);
        }

        return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
    }
}
