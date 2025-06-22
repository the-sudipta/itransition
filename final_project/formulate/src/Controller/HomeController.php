<?php

namespace App\Controller;

use App\Dto\TemplateCardDto;
use App\Repository\CommentRepository;
use App\Repository\LikeRepository;
use App\Repository\TemplateRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

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
}
