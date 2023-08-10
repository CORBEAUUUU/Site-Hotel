<?php

namespace App\Controller;


use App\Form\ReservationType;
use App\Repository\RoomRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ReservationController extends AbstractController
{


    #[Route('/reservation/resume', name: 'app_resume')]
    public function resume(SessionInterface $session): Response
    {

        if( $session->get('reservation') == null){

            return $this-> redirectToRoute('app_home');
           
        }
        return $this->render('reservation/resume.html.twig', [
            'controller_name' => 'ReservationController',
            'reservation' => $session ->get('reservation')
        ]);
    }

    #[Route('/reservation/{id}', name: 'app_reservation')]
    public function index(RoomRepository $roomRepository, $id, Request $request, SessionInterface $session ): Response
    {

        $room = $roomRepository->find($id);
      


        $form = $this->createForm(ReservationType::class);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            $startDate = $form->get('startDate')->getData();
            $endDate = $form->get('endDate')->getData();
            $nom = $form->get('nom')->getData();
            $prenom = $form->get('prenom')->getData();
            $anniversaire = $form->get('anniversaire')->getData();
            $codePostal = $form->get('codePostal')->getData();

            $reservation = [
                'prix' => $endDate,
                'startDate' => $startDate,
                'room' => $room, 
                'nom' => $nom,
                'prenom' => $prenom,
                'anniversaire' => $anniversaire,
                'codePostal' => $codePostal,
                
            ];

            $session ->set('reservation' , $reservation);
            return $this-> redirectToRoute('app_login');

        }



        return $this->render('reservation/index.html.twig', [
            'controller_name' => 'ReservationController',
            'room' => $room,
            'form' => $form,
        ]);
    }
}
