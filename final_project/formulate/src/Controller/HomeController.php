<?php

namespace App\Controller;

use App\Dto\TemplateCardDto;
use App\Entity\User;
use App\Form\RegistrationForm;
use App\Repository\CommentRepository;
use App\Repository\LikeRepository;
use App\Repository\TemplateRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home_index')]
    public function index(TemplateRepository $templateRepo, LikeRepository $likeRepo, CommentRepository $commentRepo): Response
    {
        $templates = $templateRepo->findBy(['isPublic' => true]);

        $cards = [];
        foreach ($templates as $tpl) {
            $cards[] = new TemplateCardDto(
                $tpl->getId(),
                $tpl->getTitle(),
                $tpl->getDescription(),
                $tpl->getImage(),                         // imageUrl
                $likeRepo->count(['template' => $tpl]),   // likesCount
                $commentRepo->count(['template' => $tpl]) // commentsCount
            );
        }

        return $this->render('home/index.html.twig', [
            'forms' => $cards,
        ]);
    }

    #[Route('/auth/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationForm::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var string $plainPassword */
            $plainPassword = $form->get('plainPassword')->getData();

            // encode the plain password
            $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));

            // Set the Current TimeStamp
            $user->setCreatedAt(new \DateTime()); // now(), mutable

            // Set user Role
            $user->setRoles(['ROLE_USER']);

            $entityManager->persist($user);
            $entityManager->flush();

            // do anything else you need here, like send an email

            return $this->redirectToRoute('app_home_index');
        }

        return $this->render('home/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }

    #[Route(path: '/auth/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {


        // If the user already has ROLE_USER, send them straight to registration:
        if ($this->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('app_user_index');
        }


        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('home/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route('/test', name: 'app_home_test')]
    public function test(): Response
    {
        $n = 1 / 0; // <-- throws before we ever hit "return"
        return $this->render('home/register.html.twig');

    }





}
