<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\EventType;
use App\Form\EventFilterType;
use App\Form\SubscribeEventType;
use App\Repository\EventRepository;
use App\Service\MapService;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/event")
 */
class EventController extends Controller
{
    /**
     * @Route("/", name="event_index", methods={"GET","POST"})
     */
    public function index(Request $resquest, EventRepository $eventRepository): Response
    {
        {// creation du formulaire
            $form = $this->createForm(EventFilterType::class);
            // Il n'est pas mappé
            $form->handleRequest($resquest);

            if ($form->isSubmitted() && $form->isValid()) {

                // Requête recupére la liste d'event campus
                $events = $eventRepository->filter($this->getUser(), $form->get('name')->getData(), $form->get('search')->getData(), $form->get('minDate')->getData(), $form->get('maxDate')->getData(), $form->get('organiser')->getData(), $form->get('isParticipant')->getData(), $form->get('isNotParticipant')->getData(), $form->get('archived')->getData());

                // On redirige vers la  vue
                return $this->render('event/index.html.twig', ['events' => $events, 'form' => $form->createView()]);
            }
            return $this->render('event/index.html.twig', ['events' => $eventRepository->displayWithoutFilter($this->getUser()), 'form' => $form->createView()]);
        }
    }

    /**
     * @Route("/new", name="event_new", methods={"GET","POST"})
     */
    public function new(Request $request, MapService $mapService): Response
    {
        $state = $this->getDoctrine()->getManager()->getRepository('App:State')->findBy(['name' => 'en création'])[0];

        $event = new Event();
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $start = $form->get('start')->getData();
            $now = new DateTime();
            $limit = $form->get('limitDate')->getData();
            $duration = $form->get('duration')->getData();
            $format = $duration->format('%d%h%i');
            $nullDuration = "000";

            $nbMax = $form->get('nbMax')->getData();

            //date limite d'inscription > date du jour et date de la sortie > date limite d'inscription
            if ($limit > $now && $start > $limit) {
                //durée > 0
                if ($format != $nullDuration) {
                    //le nombre de participants doit être supérieur à deux
                    if ($nbMax>2) {

                    /** @var UploadedFile $file */
                    $file = $form->get('picture')->getData();
                    if (!\is_null($file)) {
                        $fileName = md5(uniqid()) . '.' . $file->guessExtension();
                        try {
                            $file->move('../public/images/event/', $fileName);
                        } catch (FileException $e) {
                            $this->addFlash('error', "Impossible de charger l'image");
                            return $this->render('event/new.html.twig', [
                                'event' => $event,
                                'form' => $form->createView(),
                            ]);
                        }
                        $event->setPicture($fileName);
                    }

                    //Utilisateur
                    $event->setCreator($this->getUser());

                    //Campus de l'utilisateur
                    $event->setCampus($this->getUser()->getCampus());

                    //Etat par défaut ==> en création
                    $event->setState($state);

                    $address = $form->get('address')->getData('modeldata');
                    $city = $form->get('city')->getData('modeldata');
                    //Carte et vérification recherche de l'adress 
                    $location = $mapService->geocodeAddress($address, $city);
                    if ($location == null) {
                        $this->addFlash('error', "Impossible de trouver l'adresse");
                        return $this->render('event/new.html.twig', [
                            'event' => $event,
                            'form' => $form->createView(),
                        ]);
                    }
                    //Location
                    $event->setLocation($location);

                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($event);
                    $entityManager->flush();

                    return $this->redirectToRoute('event_show', [
                        'id' => $event->getId(),
                    ]);

                } else {
                        $this->addFlash('error', "Le nombre de participants doit être supérieur à 2");
                        return $this->render('event/new.html.twig', [
                            'event' => $event,
                            'form' => $form->createView(),
                        ]);
                    }
                } else {
                    $this->addFlash('error', "Veuillez renseigner une durée");
                    return $this->render('event/new.html.twig', [
                        'event' => $event,
                        'form' => $form->createView(),
                    ]);
                }
            } else {
                $this->addFlash('error', "Date invalide");
                return $this->render('event/new.html.twig', [
                    'event' => $event,
                    'form' => $form->createView(),
                ]);
            }
        }
        return $this->render('event/new.html.twig', [
            'event' => $event,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="event_show", methods={"GET","POST"})
     */
    public function show(Event $event, Request $request): Response
    {
        $subscribeEvent = new SubscribeEventType();
        $form = $this->createForm(SubscribeEventType::class, $subscribeEvent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            //gestion de l'inscription et désinscription
            if ($event->getParticipants()->contains($this->getUser())) {
                $this->getUser()->removeSubscribedEvent($event);
            } else {
                //contrôle du nb max de participants
                if ($event->getParticipants()->count()<$event->getNbMax()) {
                    $this->getUser()->addSubscribedEvent($event);
                } else {
                    $this->addFlash('error', "Inscription impossible, le nb de participants max est atteint");
                }

            }

            $entityManager->flush();
        }
        $participants = $event->getParticipants();

        return $this->render('event/show.html.twig', [
            'event' => $event,
            'participants' => $participants,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="event_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Event $event, MapService $mapService): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $address = $entityManager->getRepository('App:Location')->find($event->getLocation()->getId());
        $city = $entityManager->getRepository('App:City')->find($address->getCity()->getId());

        $idEvent = $event->getId();

        $form = $this->createForm(EventType::class, $event,
            ['address' => $address->getAddress(),
                'city' => $city->getName(),
                'idEvent' => $idEvent,
            ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $start = $form->get('start')->getData();
            $now = new DateTime();
            $limit = $form->get('limitDate')->getData();
            $duration = $form->get('duration')->getData();
            $format = $duration->format('%d%h%i');
            $nullDuration = "000";

            $nbMax = $form->get('nbMax')->getData();

            //date limite d'inscription > date du jour et date de la sortie > date limite d'inscription
            if ($limit > $now && $start > $limit) {
                //durée > 0
                if ($format != $nullDuration) {
                    //le nombre de participants doit être supérieur à deux
                    if ($nbMax>2) {

                        //changement de statut de en création à ouvert
                        $button = $form->get('publier')->isClicked();
                        if ($button) {
                            $state = $entityManager->getRepository('App:State')->findBy(['name' => 'publié'])[0];
                            $event->setState($state);
                        }
                        /** @var UploadedFile $file */
                        $file = $form->get('picture')->getData();
                        if (!\is_null($file)) {
                            // md5 creer un identifiant unique pour les images
                            $fileName = md5(uniqid()) . '.' . $file->guessExtension();
                            try {
                                $file->move('../public/images/event/', $fileName);
                            } catch (FileException $e) {
                                $this->addFlash('error', "Impossible de charger l'image");
                                return $this->render('event/edit.html.twig', [
                                    'event' => $event,
                                    'form' => $form->createView(),
                                ]);
                            }
                            $event->setPicture($fileName);
                        }
                        // Carte
                        $address = $form->get('address')->getData('modeldata');
                        $city = $form->get('city')->getData('modeldata');

                        $location = $mapService->geocodeAddress($address, $city);
                        if ($location == null) {
                            $this->addFlash('error', "Impossible de trouver l'adresse");
                            return $this->render('event/edit.html.twig', [
                                'event' => $event,
                                'form' => $form->createView(),
                            ]);
                        }
                        //Location
                        $event->setLocation($location);

                        $entityManager->persist($event);
                        $entityManager->flush();

                        return $this->redirectToRoute('event_show', [
                            'id' => $event->getId(),
                        ]);

                    } else {
                        $this->addFlash('error', "Le nombre de participants doit être supérieur à 2");
                        return $this->render('event/new.html.twig', [
                            'event' => $event,
                            'form' => $form->createView(),
                        ]);
                    }
                    } else {
                        $this->addFlash('error', "Veuillez renseigner une durée");
                        return $this->render('event/edit.html.twig', [
                            'event' => $event,
                            'form' => $form->createView(),
                        ]);
                    }
                } else {
                    $this->addFlash('error', "Date invalide");
                    return $this->render('event/edit.html.twig', [
                        'event' => $event,
                        'form' => $form->createView(),
                    ]);
                }
            }
        return $this->render('event/edit.html.twig', [
            'event' => $event,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/{id}", name="event_delete", methods={"DELETE"})
     */
    public
    function delete(Request $request, Event $event): Response
    {
        if ($this->isCsrfTokenValid('delete' . $event->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($event);
            $entityManager->flush();
        }
        return $this->redirectToRoute('event_index');
    }

}