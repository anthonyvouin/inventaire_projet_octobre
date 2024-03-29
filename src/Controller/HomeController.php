<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Repository\ReservationRepository;
use App\Service\CallApiService;
use App\Service\MailService;
use stdClass;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    public function __construct()
    {
        $_SESSION['truc'] = 1;

    }


    #[Route('/', name: 'app_home')]
    public function index(ReservationRepository $reservationRepository, CallApiService $callApiService): Response
    {

        $eleves   = $reservationRepository->findBy(array('isRendered' => false));

        $students = $callApiService->getData();
        $array = [];
        // double forech pour compare le studient id de la revservation et de l'api
        foreach ($students as $student) {
            foreach ($eleves as $reservation) {
                if ($student['id'] == $reservation->getStudentId()) {
                    $item = new stdClass();
                    $item->id = $reservation->getId();
                    $item->material = $reservation->getMaterial();
                    $item->empruntDate = $reservation->getEmpruntDate();
                    $item->isRendered = $reservation->isIsRendered();
                    $item->rendered = $reservation->getRendered();
                    $item->studentId = $student['mail'];

                    array_push($array, $item);
                }
            }
        }

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'eleves' => $array
        ]);
    }
  



    


    #[Route('/emailRappel/{id}', name: 'app_emailrappel', methods: ["POST"])]


    public function mail(MailService $mailService, Reservation $reservation)
    {
        // $destinaire = $reservation->getEmail();
        // $messageSubject = "Mail de relance";
        // $materiel = $reservation->getMaterial()->getName();
        // $dateEmprunt = $reservation->getEmpruntDate()->format('d-m-Y H:i:s');
        // $dateRendu = $reservation->getRendered()->format('d-m-Y H:i:s');
        // $messageBody = "
               
        // <h1>Mail de relance matériel</h1>
        // <p>
        // A la date a la quelle vous avez emprunté : $dateEmprunt  <br/>
        // Vous avez emprunté le matériel  : $materiel <br/>
        // La date à rendre :  $dateRendu    <br/>
        // </p>
        // ";

        // $mailService->sendMail($destinaire, $messageSubject, $messageBody);

        // //    return new Response('yes' , Response::HTTP_OK);



        // return $this->redirectToRoute('app_home', [],Response::HTTP_SEE_OTHER);
    }

}
