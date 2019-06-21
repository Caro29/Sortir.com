<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\PasswordFormType;
use App\Form\UserType;
use App\Repository\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/user")
 */
class UserController extends Controller
{
    /**
     * @Route("/", name="user_index", methods={"GET"})
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    /**
     * @Route("/{id}/showpart", name="participant_show", methods={"GET"})
     */
    public function participantShow(User $user): Response
    {
        return $this->render('user/participantshow.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/{id}/show", name="user_show", methods={"GET"})
     */
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="user_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, User $user): Response
    {
        if ($this->getUser() == $user) {
            $form = $this->createForm(UserType::class, $user);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                /** @var UploadedFile $file */
                $file = $form->get('picture')->getData();
                if (!\is_null($file)) {
                    $fileName = md5(uniqid()) . '.' . $file->guessExtension();
                    try {
                        $file->move('../public/images/profil/', $fileName);
                    } catch (FileException $e) {

                    }
                    $user->setPicture($fileName);
                }
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();

                return $this->redirectToRoute('user_show', [
                    'id' => $user->getId(),
                ]);
            }

            return $this->render('user/edit.html.twig', [
                'user' => $user,
                'form' => $form->createView(),
            ]);
        } else {
            return $this->redirectToRoute('event_index');
        }

    }

    /**
     * @Route("/{id}/pw", name="password", methods={"GET","POST"})
     */
    public function modifyPassword(Request $request, User $user, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        if ($this->getUser() == $user) {
            $form = $this->createForm(PasswordFormType::class, $user);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                // encode the plain password
                $user->setPassword(
                    $passwordEncoder->encodePassword(
                        $user,
                        $form->get('plainPassword')->getData()
                    )
                );
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();

                return $this->redirectToRoute('user_show', [
                    'id' => $user->getId(),
                ]);
            }

            return $this->render('user/password.html.twig', [
                'user' => $user,
                'form' => $form->createView(),
            ]);
        } else {
            return $this->redirectToRoute('event_index');
        }

    }

    /**
     * @Route("/{id}", name="user_delete", methods={"DELETE"})
     */
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('user_index');
    }
}
