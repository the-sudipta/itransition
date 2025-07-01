<?php

namespace App\Controller;

use App\Dto\TemplateCardDto;
use App\Repository\CommentRepository;
use App\Repository\LikeRepository;
use App\Repository\TemplateRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user_index')]
    public function index(TemplateRepository $templateRepo, LikeRepository $likeRepo, CommentRepository  $commentRepo): Response
    {
        // if no user, redirect immediately
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        // 2) fetch only the templates (forms) that belong to this user
        $user = $this->getUser();

        // example: Template has an 'owner' field
        $templates = $templateRepo->findBy(
            ['isPublic' => true],
            ['createdAt' => 'DESC']
        );

        // 3) build same DTOs as on landing page
        $cards = [];
        foreach ($templates as $tpl) {
            $cards[] = new TemplateCardDto(
                $tpl->getId(),
                $tpl->getTitle(),
                $tpl->getDescription(),
                $tpl->getImage(),                       // image URL
                $likeRepo->count(['template' => $tpl]),   // likesCount
                $commentRepo->count(['template' => $tpl]) // commentsCount
            );
        }

        // 4) render user dashboard, passing 'forms' for Twig loop
        return $this->render('user/index.html.twig', [
            'forms' => $cards,
        ]);
    }
}
