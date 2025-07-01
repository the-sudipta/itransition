<?php

namespace App\Controller;

use App\Dto\TemplateCardDto;
use App\Entity\Comment;
use App\Entity\Like;
use App\Entity\Template;
use App\Repository\CommentRepository;
use App\Repository\LikeRepository;
use App\Repository\TemplateRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;

final class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user_index')]
    public function index(TemplateRepository $templateRepo, LikeRepository $likeRepo, CommentRepository  $commentRepo, Request $request,): Response
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

        // 4) Get all the likes of the user and then map them template-wise
        $likes = $likeRepo->findBy(['user' => $this->getUser()]);
        $likedIds = array_map(fn($l) => $l->getTemplate()->getId(), $likes);

        // just after you load $templates…
        $allComments = [];
        foreach ($templates as $tpl) {
            $allComments[$tpl->getId()] = $commentRepo
                ->findBy(['template' => $tpl], ['createdAt' => 'ASC']);
        }
        // read our flash‐bag for which modal to open
        $openModal = $request
            ->getSession()
            ->getFlashBag()
            ->get('open_comment_modal', []);

        // 6) render user dashboard, passing 'forms' for Twig loop
        return $this->render('user/index.html.twig', [
            'forms' => $cards,
            'liked_ids' => $likedIds,
            'comments_by' => $allComments,
            'open_modal'  => $openModal,
        ]);
    }

    #[Route('/user/template/{id}/toggle-like', name: 'app_user_toggle_like', methods: ['POST'])]
    public function toggleLike(
        int $id,
        Request $request,
        TemplateRepository $templateRepo,
        LikeRepository $likeRepo,
        EntityManagerInterface $em
    ): RedirectResponse {
        // CSRF check
        $submittedToken = $request->request->get('_token');
        if (! $this->isCsrfTokenValid('toggle_like'.$id, $submittedToken)) {
            throw new InvalidCsrfTokenException();
        }

        $user     = $this->getUser();
        $template = $templateRepo->find($id);

        // find existing like
        $existing = $likeRepo->findOneBy([
            'template' => $template,
            'user'     => $user,
        ]);

        if ($existing) {
            $em->remove($existing);
        } else {
            $like = new Like();
            $like->setTemplate($template)
                ->setUser($user);
            $em->persist($like);
        }

        $em->flush();

        // back to dashboard
        return $this->redirectToRoute('app_user_index');
    }

    #[Route('/user/template/{id}/add-comment', name:'app_user_add_comment', methods:['POST'])]
    public function addComment(
        int $id,
        Request $request,
        TemplateRepository $tplRepo,
        EntityManagerInterface $em
    ): RedirectResponse {
        // CSRF check
        if ( ! $this->isCsrfTokenValid('add_comment'.$id, $request->request->get('_token')) ) {
            throw $this->createAccessDeniedException();
        }

        $template = $tplRepo->find($id);
        $user     = $this->getUser();
        $text     = trim($request->request->get('comment_text', ''));

        if ( $text !== '' ) {
            $comment = new Comment();
            $comment
                ->setTemplate($template)
                ->setUser($user)
                ->setContent($text)
                ->setCreatedAt(new \DateTime());
            $em->persist($comment);
            $em->flush();
        }

        // tell index() to re-open this modal
        $this->addFlash('open_comment_modal', $id);

        return $this->redirectToRoute('app_user_index');
    }


    #[Route('/user/form', name: 'app_user_forms')]
    public function forms(
        TemplateRepository $templateRepo,
        LikeRepository $likeRepo,
        CommentRepository $commentRepo,
        Request $request
    ): Response
    {
        $user = $this->getUser();
        if (! $user) {
            return $this->redirectToRoute('app_login');
        }

        // 1. Only templates *owned* by this user, sorted newest first
        $templates = $templateRepo->findBy(
            ['user' => $this->getUser()],
            ['createdAt' => 'DESC']
        );

        // 2. Build a plain array for Twig, including all needed bits
        $forms = [];
        foreach ($templates as $tpl) {
            $forms[] = [
                'id'            => $tpl->getId(),
                'title'         => $tpl->getTitle(),
                'description'   => $tpl->getDescription(),
                'createdAt'     => $tpl->getCreatedAt(),
                'isPublic'      => $tpl->isPublic(),
                'likesCount'    => $likeRepo->count(['template' => $tpl]),
                'commentsCount' => $commentRepo->count(['template' => $tpl]),
            ];
        }

        // 3. Likes & comments for modal logic stay the same…
        $likes    = $likeRepo->findBy(['user' => $user]);
        $likedIds = array_map(fn($l) => $l->getTemplate()->getId(), $likes);

        $allComments = [];
        foreach ($templates as $tpl) {
            $allComments[$tpl->getId()] = $commentRepo
                ->findBy(['template' => $tpl], ['createdAt' => 'ASC']);
        }

        $openModal = $request
            ->getSession()
            ->getFlashBag()
            ->get('open_comment_modal', []);

//        dd($forms);

        // 4. Render with our new “forms” array
        return $this->render('user/show_all_forms.html.twig', [
            'forms'       => $forms,
            'liked_ids'   => $likedIds,
            'comments_by' => $allComments,
            'open_modal'  => $openModal,
        ]);
    }


    #[Route('/user/forms/delete', name: 'app_user_forms_delete', methods: ['POST'])]
    public function deleteForm(Request $request, EntityManagerInterface $em): Response
    {
        $ids = array_filter(explode(',', $request->request->get('ids', '')));
        if ($ids) {
            $repo = $em->getRepository(Template::class);
            foreach ($ids as $id) {
                if ($tpl = $repo->find($id)) {
                    $em->remove($tpl);
                }
            }
            $em->flush();
            $this->addFlash('success', count($ids).' form(s) deleted.');
        } else {
            $this->addFlash('warning', 'No forms selected.');
        }

        return $this->redirectToRoute('app_user_forms');
    }


    #[Route('/user/forms/toggle-visibility', name: 'app_user_forms_toggle', methods: ['POST'])]
    public function toggleVisibility(Request $request, EntityManagerInterface $em)
    {
        $ids = array_filter(explode(',', $request->request->get('ids', '')));
        if ($ids) {
            $repo = $em->getRepository(Template::class);
            foreach ($ids as $id) {
                if ($tpl = $repo->find($id)) {
                    $tpl->setIsPublic(!$tpl->isPublic());
                }
            }
            $em->flush();
            $this->addFlash('success', 'Visibility toggled for '.count($ids).' form(s).');
        } else {
            $this->addFlash('warning', 'No forms selected.');
        }
        return $this->redirectToRoute('app_user_forms');
    }


}
