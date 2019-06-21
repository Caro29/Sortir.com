<?php

namespace App\Controller;

use App\Entity\CancelEvent;
use App\Entity\Event;
use App\Form\CancelEventType;
use App\Repository\CancelEventRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/cancel/event")
 */
class CancelEventController extends Controller
{
    /**
     * @Route("/", name="cancel_event_index", methods={"GET"})
     */
    public function index(CancelEventRepository $cancelEventRepository): Response
    {
        return $this->render('cancel_event/index.html.twig', [
            'cancel_events' => $cancelEventRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new{eventNumber}", name="cancel_event_new", defaults={"eventNumber":1}, methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $cancelEvent = new CancelEvent();
        $entityManager = $this->getDoctrine()->getManager();

        $form = $this->createForm(CancelEventType::class, $cancelEvent,
            [
                'idEvent' => $request->get('id'),
            ]);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            // Récupération id de l'état 'annulé'
            $state = $entityManager->getRepository('App:State')->findBy(['name' => 'annulé'])[0];

            // Récupération l'entité EVENT à annuler
            $event = $entityManager->getRepository('App:Event')->find($request->get('id'));

            // Update statut d'un event
            $entityManager->getRepository('App:Event')->updateState($request, $state);

            // Enregistrement de la l'annulation
            $cancelEvent->setRelation($event);
            $entityManager->persist($cancelEvent);
            $entityManager->flush();

            return $this->redirectToRoute('event_index');
        }

        return $this->render('cancel_event/new.html.twig', [
            'cancel_event' => $cancelEvent,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/{id}", name="cancel_event_show", methods={"GET"})
     */
    public function show(CancelEvent $cancelEvent): Response
    {
        return $this->render('cancel_event/show.html.twig', [
            'cancel_event' => $cancelEvent,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="cancel_event_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, CancelEvent $cancelEvent): Response
    {
        $form = $this->createForm(CancelEventType::class, $cancelEvent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('cancel_event_index', [
                'id' => $cancelEvent->getId(),
            ]);
        }

        return $this->render('cancel_event/edit.html.twig', [
            'cancel_event' => $cancelEvent,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="cancel_event_delete", methods={"DELETE"})
     */
    public function delete(Request $request, CancelEvent $cancelEvent): Response
    {
        if ($this->isCsrfTokenValid('delete' . $cancelEvent->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($cancelEvent);
            $entityManager->flush();
        }

        return $this->redirectToRoute('cancel_event_index');
    }
}
