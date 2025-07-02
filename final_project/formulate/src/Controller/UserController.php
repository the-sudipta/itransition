<?php

namespace App\Controller;

use App\Dto\TemplateCardDto;
use App\Entity\Answer;
use App\Entity\Comment;
use App\Entity\FormSubmit;
use App\Entity\Like;
use App\Entity\Option;
use App\Entity\Question;
use App\Entity\Template;
use App\Entity\TemplateTag;
use App\Form\ChangePasswordType;
use App\Repository\CommentRepository;
use App\Repository\FormSubmitRepository;
use App\Repository\LikeRepository;
use App\Repository\OptionRepository;
use App\Repository\QuestionRepository;
use App\Repository\TemplateRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Entity\User;

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


    #[Route('/user/forms/create', name: 'app_user_form_create')]
    public function createForms(Request $request, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {
        // Verify token. Token is needed for =>
        if ($request->isMethod('POST')) {

            $submittedToken = $request->request->get('_token');
            if (!$this->isCsrfTokenValid('create_form', $submittedToken)) {
                throw $this->createAccessDeniedException('Invalid CSRF token');
            }

            // handle image
            $uploadDir = $this->getParameter('kernel.project_dir').'/public/uploads/forms';
            $imageFile = $request->files->get('image');
            if ($imageFile) {
                $safeName = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $fileName = $slugger->slug($safeName).'_'.uniqid().'.'.$imageFile->guessExtension();
                $imageFile->move($uploadDir, $fileName);
            } else {
                $fileName = 'default_form.png';
            }

            // create template
            $now      = new \DateTime();
            $user     = $this->getUser();
            $template = new Template();
            $template->setTitle($request->request->get('title'));
            $template->setDescription($request->request->get('description', ''));
            $template->setIsPublic($request->request->has('is_public'));
            $template->setImage('uploads/forms/'.$fileName);

            $template->setTopic($request->request->get('type'));
            foreach ($request->request->all('tags', []) as $tagName) {
                $tag = new TemplateTag();
                $tag->setTag($tagName);
                $tag->setTemplate($template);
                $em->persist($tag);
            }

            $template->setVersion(1);
            $template->setCreatedAt($now);
            $template->setLastUpdatedAt($now);
            $template->setUser($user);

            $em->persist($template);

            // questions & options
            $questions = $request->request->all('questions');
            foreach ($questions as $pos => $q) {
                $question = new Question();
                $question->setTitle($q['text']);
                $question->setDescription("N/A");
                $question->setShowInResults(0);
                $question->setType($q['type']);
                $question->setPosition((int)$pos);
                $question->setTemplate($template);
                $em->persist($question);

                if (in_array($q['type'], ['radio','checkbox']) && isset($q['options'])) {
                    foreach ($q['options'] as $oPos => $optText) {
                        $option = new Option();
                        $option->setText($optText);
                        $option->setPosition((int)$oPos);
                        $option->setQuestion($question);
                        $em->persist($option);
                    }
                }
            }

            $em->flush();
            $this->addFlash('success', 'Form created successfully.');
            return $this->redirectToRoute('app_user_forms');
        }

        return $this->render('user/create_form.html.twig');
    }

    #[Route('/user/forms/{id}/edit', name: 'app_user_form_edit', methods: ['GET','POST'])]
    public function editForm(
        int $id,
        Request $request,
        EntityManagerInterface $em,
        SluggerInterface $slugger,
        CsrfTokenManagerInterface $csrfManager
    ): Response {
        // 1) Load existing Template
        $template = $em->getRepository(Template::class)->find($id);
        if (!$template || $template->getUser() !== $this->getUser()) {
            throw $this->createNotFoundException('Form not found');
        }

        // 2) Handle POST (update)
        if ($request->isMethod('POST')) {
            // CSRF
            $token = $request->request->get('_token');
            if (!$this->isCsrfTokenValid('edit_form_'.$id, $token)) {
                throw $this->createAccessDeniedException('Invalid CSRF token');
            }

            // Image upload (optional replace)
            $uploadDir = $this->getParameter('kernel.project_dir').'/public/uploads/forms';
            if ($file = $request->files->get('image')) {
                $safeName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $ext      = $file->guessExtension();
                $newName  = $slugger->slug($safeName).'_'.uniqid().'.'.$ext;
                $file->move($uploadDir, $newName);
                $template->setImage('uploads/forms/'.$newName);
            }

            // Scalars & version/timestamps
            $template
                ->setTitle($request->request->get('title'))
                ->setDescription($request->request->get('description', ''))
                ->setIsPublic($request->request->has('is_public'))
                ->setTopic($request->request->get('type'))
                ->setVersion($template->getVersion() + 1)
                ->setLastUpdatedAt(new \DateTime())
            ;

            // Tags: remove old, add new
            foreach ($template->getTemplateTags() as $oldTag) {
                $em->remove($oldTag);
            }
            $template->getTemplateTags()->clear();
            foreach ($request->request->all('tags', []) as $name) {
                $tag = new TemplateTag();
                $tag->setTag($name)->setTemplate($template);
                $em->persist($tag);
            }

            // Questions & Options: remove old, add new
            foreach ($template->getQuestions() as $oldQ) {
                $em->remove($oldQ);
            }
            $template->getQuestions()->clear();
            foreach ($request->request->all('questions', []) as $pos => $qData) {
                $q = new Question();
                $q->setTitle($qData['text'])
                    ->setType($qData['type'])
                    ->setDescription("N/A")
                    ->setShowInResults(0)
                    ->setPosition((int)$pos)
                    ->setTemplate($template);
                $em->persist($q);

                if (in_array($qData['type'], ['radio','checkbox'], true)) {
                    foreach ($qData['options'] ?? [] as $oPos => $optText) {
                        $opt = new Option();
                        $opt->setText($optText)
                            ->setPosition((int)$oPos)
                            ->setQuestion($q);
                        $em->persist($opt);
                    }
                }
            }

            $em->flush();
            $this->addFlash('success', 'Form updated successfully.');
            return $this->redirectToRoute('app_user_forms');
        }

        // 3) Render Edit form (GET)
        return $this->render('user/edit_form.html.twig', [
            'template' => $template,
            'csrf_token' => $csrfManager->getToken('edit_form_'.$id)->getValue(),
        ]);
    }

    #[Route('/user/forms/{id}/submit', name: 'app_user_form_submit', methods: ['GET','POST'])]
    public function submitForm(
        int $id,
        Request $request,
        EntityManagerInterface $em,
        FormSubmitRepository $fsRepo,
        QuestionRepository $qRepo,
        OptionRepository $optRepo,
        CsrfTokenManagerInterface $csrfManager
    ): Response {
        // 1) load form
        $template = $em->getRepository(Template::class)->find($id);
        if (!$template || !$template->isPublic()) {
            throw $this->createNotFoundException('Form not found or not public');
        }

        $user = $this->getUser();
        // 2) one‐time submission check
        if ($fsRepo->findOneBy(['template'=>$template, 'user'=>$user])) {
            $this->addFlash('warning','You have already submitted this form.');
            return $this->redirectToRoute('app_user_index');
        }

        // 3) handle POST
        if ($request->isMethod('POST')) {
            $submittedToken = $request->request->get('_token');
            if (!$this->isCsrfTokenValid('submit_form_'.$id, $submittedToken)) {
                throw $this->createAccessDeniedException('Invalid CSRF token');
            }

            $fs = new FormSubmit();
            $fs->setTemplate($template)
                ->setUser($user)
                ->setCreatedAt(new \DateTime());
            $em->persist($fs);

            $answers = $request->request->all('answers', []);
            foreach ($answers as $qId => $val) {
                $question = $qRepo->find($qId);
                if (!$question) {
                    continue;
                }

                // TEXT answer
                if ($question->getType() === 'text') {
                    $ans = new Answer();
                    $ans
                        ->setFormSubmit($fs)
                        ->setQuestion($question)
                        ->setAnswerText(trim($val))
                    ;
                    $em->persist($ans);
                }

                // SINGLE-CHOICE (radio)
                elseif ($question->getType() === 'radio') {
                    $opt = $optRepo->find((int)$val);
                    if ($opt) {
                        $ans = new Answer();
                        $ans
                            ->setFormSubmit($fs)
                            ->setQuestion($question)
                            ->setChoosenOption($opt)
                            ->setAnswerText($opt->getText())    // <-- set text from option!
                        ;
                        $em->persist($ans);
                    }
                }

                // MULTI-SELECT (checkbox)
                elseif ($question->getType() === 'checkbox' && is_array($val)) {
                    foreach ($val as $optId) {
                        $opt = $optRepo->find((int)$optId);
                        if (!$opt) {
                            continue;
                        }
                        $ans = new Answer();
                        $ans
                            ->setFormSubmit($fs)
                            ->setQuestion($question)
                            ->setChoosenOption($opt)
                            ->setAnswerText($opt->getText())    // <-- set text here too!
                        ;
                        $em->persist($ans);
                    }
                }
            }

            $em->flush();
            $this->addFlash('success','Thank you—your responses have been recorded!');
            return $this->redirectToRoute('app_user_index');
        }

        // 4) render form
        return $this->render('user/submit_form.html.twig', [
            'template'   => $template,
            'csrf_token' => $csrfManager->getToken('submit_form_'.$id)->getValue(),
        ]);
    }


//    ##################### Response Tab ######################

    // Shows all forms that received responses (created by the logged-in user)
    #[Route('/user/forms/responded', name: 'app_user_forms_responded', methods: ['GET'])]
    public function respondedForms(TemplateRepository $templates): Response
    {
        // fetch all templates you own that have >=1 submission
        $forms = $templates->createQueryBuilder('t')
            ->join('t.formSubmits','fs')
            ->andWhere('t.user = :me')
            ->setParameter('me', $this->getUser())
            ->groupBy('t.id')
            ->orderBy('t.createdAt', 'DESC')
            ->getQuery()
            ->getResult();

        return $this->render('user/show_all_responded_forms.html.twig', [
            'forms' => $forms,
        ]);
    }

    // Shows all responses submitted for a specific form (selected by the creator)
    #[Route('/user/forms/{id}/responses', name: 'app_user_form_responses', methods: ['GET','POST'])]
    public function responses(
        int $id,
        Request $request,
        TemplateRepository $templates,
        FormSubmitRepository $fsRepo,
        EntityManagerInterface $em
    ): Response {
        $template = $templates->find($id);
        if(!$template || $template->getUser() !== $this->getUser()) {
            throw $this->createNotFoundException();
        }

        // Handle bulk-delete of submissions
        if ($request->isMethod('POST')) {
            $ids = $request->request->all('ids', []);
            foreach ($ids as $sid) {
                if($fs = $fsRepo->find($sid)) {
                    $em->remove($fs);
                }
            }
            $em->flush();
            $this->addFlash('success','Deleted '.count($ids).' response(s).');
            return $this->redirectToRoute('app_user_form_responses',['id'=>$id]);
        }

        // GET: list all submits for this form
        $submits = $fsRepo->findBy(['template'=>$template]);
        return $this->render('user/show_all_responses.html.twig', [
            'template' => $template,
            'submits'  => $submits,
        ]);
    }

    // Shows a single specific response to a form
    #[Route('/user/forms/{formId}/responses/{responseId}', name: 'app_user_single_response', methods: ['GET'])]
    public function singleResponse(
        int $formId,
        int $responseId,
        TemplateRepository $templates,
        FormSubmitRepository $fsRepo
    ): Response {
        $template = $templates->find($formId);
        $fs       = $fsRepo->find($responseId);
        if (
            !$template
            || $template->getUser() !== $this->getUser()
            || !$fs
            || $fs->getTemplate()->getId()!==$formId
        ) {
            throw $this->createNotFoundException();
        }

        return $this->render('user/user_response.html.twig', [
            'template' => $template,
            'submit'   => $fs,
        ]);
    }

//    ####################### USER PROFILE ###############################

    #[Route('/user/profile', name: 'app_user_profile', methods: ['GET','POST'])]
    public function profile(
        Request $request,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $passwordHasher,
        CsrfTokenManagerInterface $csrfManager
    ): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        // build a simple form model for changing password
        $form = $this->createForm(ChangePasswordType::class, null, [
            'action' => $this->generateUrl('app_user_profile'),
            'method' => 'POST',
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // CSRF is already checked by the form
            $data         = $form->getData();
            $newPassword = $form->get('newPassword')->getData();
            $encoded     = $passwordHasher->hashPassword($user, $newPassword);
            $user->setPassword($encoded);
            $em->flush();

            $this->addFlash('success', 'Your password has been updated.');
            return $this->redirectToRoute('app_user_profile');
        }

        return $this->render('user/profile.html.twig', [
            'email' => $user->getEmail(),
            'form'  => $form->createView(),
        ]);
    }




}
