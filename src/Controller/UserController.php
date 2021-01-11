<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\EditFormType;
use App\Form\RegistrationFormType;
use Doctrine\DBAL\Types\DateType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class UserController extends AbstractController
{
//    /**
//     * @Route("/user", name="user")
//     */
//    public function index(): Response
//    {
//        return $this->render('user/index.html.twig', [
//            'controller_name' => 'UserController',
//        ]);
//    }

    /**
     * @Route ("/register", name="register")
     * @return Response
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('index');
        }

        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('login');
        }

        return $this->render("user/register.html.twig", [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route ("/login", name="login")
     * @return Response
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
         if ($this->getUser()) {
             return $this->redirectToRoute('index');
         }

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render("user/login.html.twig", [
            'last_username' => $lastUsername,
            'error' => $error
        ]);
    }

    /**
     * @Route ("/profile", name="profile")
     * @return Response
     */
    public function profile() :Response
    {
        $user = $this->getUser();

        return $this->render("user/profile.html.twig", [
            "username" => $user->getUsername(),
            "firstname" => $user->getFirstname(),
            "name" => $user->getName(),
            "email" => $user->getEmail(),
            "birthdate" => $user->getBirthdate()
        ]);

    }

    /**
     * @Route ("/edit", name="edit_profile")
     * @param Request $request
     * @return Response
     */
    public function edit(Request $request) :Response
    {
        $user = $this->getUser();
        $form = $this->createForm(EditFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('profile');
        }

        return $this->render("user/edit.html.twig", [
            'editForm' => $form->createView(),
            "username" => $user->getUsername(),
            "firstname" => $user->getFirstname(),
            "name" => $user->getName(),
            "email" => $user->getEmail(),
            "birthdate" => $user->getBirthdate()
        ]);
    }

    /**
     * @Route ("/delete", name="delete_user")
     * @param Request $request
     * @param User $user
     * @return Response
     */
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();

            $this->addFlash('sup', 'Account deleted success');
        }

        return $this->redirectToRoute('index');
    }

    /**
     * @Route("/logout", name="app_logout", methods={"GET"})
     */
    public function logout()
    {
    }
}
