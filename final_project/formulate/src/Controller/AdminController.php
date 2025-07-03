<?php

namespace App\Controller;

use App\Entity\Template;
use App\Entity\User;
use App\Form\ChangePasswordType;
use App\Repository\FormSubmitRepository;
use App\Repository\UserRepository;
use App\Repository\TemplateRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class AdminController extends AbstractController
{

    #[Route('/admin', name:'admin_dashboard', methods:['GET'])]
    #[IsGranted("ROLE_ADMIN", statusCode: 403, message: 'Access denied.')]
    public function dashboard(): Response
    {
        if (!$this->getUser()) {
            $this->addFlash('warning', 'Please log in to continue.');
            return $this->redirectToRoute('app_login');
        }
        return $this->render('admin/dashboard.html.twig');
    }

    // ###########################  ADMIN USERS  ###################################################
    #[Route('/admin/users', name: 'admin_users', methods: ['GET'])]
    #[IsGranted("ROLE_ADMIN", statusCode: 403, message: 'Access denied.')]
    public function listUsers(UserRepository $userRepo): Response
    {

        if (!$this->getUser()) {
            $this->addFlash('warning', 'Please log in to continue.');
            return $this->redirectToRoute('app_login');
        }

        $users = $userRepo->findAll();
        return $this->render('admin/users.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/admin/users/create', name: 'admin_user_create', methods: ['GET','POST'])]
    #[IsGranted("ROLE_ADMIN", statusCode: 403, message: 'Access denied.')]
    public function createUser(
        Request $request,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $hasher
    ): Response {

        if (!$this->getUser()) {
            $this->addFlash('warning', 'Please log in to continue.');
            return $this->redirectToRoute('app_login');
        }

        if ($request->isMethod('POST')) {
            $email    = $request->request->get('email');
            $password = $request->request->get('password');
            $roles    = array_map('trim', explode(',', $request->request->get('roles', '')));
            $user = new User();
            $user->setEmail($email);
            $user->setRoles($roles);
            $user->setPassword($hasher->hashPassword($user, $password));
            $user->setCreatedAt(new \DateTime());
            $em->persist($user);
            $em->flush();
            $this->addFlash('success', 'User created successfully.');
            return $this->redirectToRoute('admin_users');
        }

        return $this->render('admin/create_user.html.twig');
    }

    #[Route('/admin/users/edit/{id}', name: 'admin_user_edit', methods: ['GET','POST'])]
    #[IsGranted("ROLE_ADMIN", statusCode: 403, message: 'Access denied.')]
    public function editUser(
        int $id,
        Request $request,
        EntityManagerInterface $em,
        UserRepository $userRepo,
        UserPasswordHasherInterface $hasher
    ): Response {

        if (!$this->getUser()) {
            $this->addFlash('warning', 'Please log in to continue.');
            return $this->redirectToRoute('app_login');
        }

        $user = $userRepo->find($id);
        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }

        if ($request->isMethod('POST')) {
            $email    = $request->request->get('email');
            $password = $request->request->get('password');
            $roles    = array_map('trim', explode(',', $request->request->get('roles', '')));
            $user->setEmail($email);
            $user->setRoles($roles);
            if ($password) {
                $user->setPassword($hasher->hashPassword($user, $password));
            }
            $em->flush();
            $this->addFlash('success', 'User updated successfully.');
            return $this->redirectToRoute('admin_users');
        }

        return $this->render('admin/edit_user.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/admin/users/delete", name="admin_users_delete", methods={"POST"})
     */
    #[Route('/admin/users/delete', name:'admin_users_delete', methods:['POST'])]
    #[IsGranted("ROLE_ADMIN", statusCode: 403, message: 'Access denied.')]
    public function deleteUsers(Request $request, EntityManagerInterface $em): Response
    {

        if (!$this->getUser()) {
            $this->addFlash('warning', 'Please log in to continue.');
            return $this->redirectToRoute('app_login');
        }

        $ids = array_filter(explode(',', $request->request->get('ids', '')));
//        dd($ids);
        if ($ids) {
            $repo = $em->getRepository(User::class);
            foreach ($ids as $id) {
                if ($tpl = $repo->find($id)) {
                    $em->remove($tpl);
                }
            }
            $em->flush();
            $this->addFlash('success', count($ids).' user(s) deleted.');
        } else {
            $this->addFlash('warning', 'No user selected.');
        }
        return $this->redirectToRoute('admin_users');
    }





    // ###########################  ADMIN FORMS  ###################################################

    #[Route('/admin/forms', name:'admin_forms')]
    #[IsGranted("ROLE_ADMIN", statusCode: 403, message: 'Access denied.')]
    public function listForms(TemplateRepository $repo): Response
    {

        if (!$this->getUser()) {
            $this->addFlash('warning', 'Please log in to continue.');
            return $this->redirectToRoute('app_login');
        }

        return $this->render('admin/forms.html.twig', [
            'forms' => $repo->findBy([], ['createdAt' => 'DESC']),
        ]);
    }

    /** Toggle visibility for selected forms */
    #[Route('/admin/forms/toggle', name:'admin_forms_toggle', methods:['POST'])]
    #[IsGranted("ROLE_ADMIN", statusCode: 403, message: 'Access denied.')]
    public function toggleForms(Request $request, EntityManagerInterface $em): Response
    {

        if (!$this->getUser()) {
            $this->addFlash('warning', 'Please log in to continue.');
            return $this->redirectToRoute('app_login');
        }

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
        return $this->redirectToRoute('admin_forms');
    }

    /** Delete selected forms */
    #[Route('/admin/forms/delete', name:'admin_forms_delete', methods:['POST'])]
    #[IsGranted("ROLE_ADMIN", statusCode: 403, message: 'Access denied.')]
    public function deleteForms(Request $request, EntityManagerInterface $em): Response
    {

        if (!$this->getUser()) {
            $this->addFlash('warning', 'Please log in to continue.');
            return $this->redirectToRoute('app_login');
        }

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

        return $this->redirectToRoute('admin_forms');
    }

    /** Show all submissions for one form */

    #[Route('/admin/forms/{id}/responses', name: 'admin_form_responses', methods: ['GET','POST'])]
    #[IsGranted("ROLE_ADMIN", statusCode: 403, message: 'Access denied.')]
    public function responses(
        int $id,
        Request $request,
        TemplateRepository $templateRepo,
        FormSubmitRepository $submitRepo,
        EntityManagerInterface $em
    ): Response {

        if (!$this->getUser()) {
            $this->addFlash('warning', 'Please log in to continue.');
            return $this->redirectToRoute('app_login');
        }

        // 1) Load the form template
        $template = $templateRepo->find($id);
        if (!$template) {
            throw $this->createNotFoundException("Form #{$id} not found");
        }

        // 2) If this is POST → delete selected responses
        if ($request->isMethod('POST')) {
            // Grab the comma-separated IDs
            $ids = array_filter(explode(',', $request->request->get('ids', '')));
            $deleted = 0;
            foreach ($ids as $respId) {
                if ($resp = $submitRepo->find($respId)) {
                    $em->remove($resp);
                    $deleted++;
                }
            }
            $em->flush();
            $this->addFlash('success', "Deleted {$deleted} response(s).");

            // Redirect back to the same list
            return $this->redirectToRoute('admin_form_responses', ['id' => $id]);
        }

        // 3) GET → just render the table
        $responses = $submitRepo->findBy(['template' => $template]);
        return $this->render('admin/form_responses.html.twig', [
            'template'  => $template,
            'responses' => $responses,
        ]);
    }

    /** View single response */
    #[Route('/admin/forms/{formId}/responses/{responseId}', name:'admin_form_response')]
    #[IsGranted("ROLE_ADMIN", statusCode: 403, message: 'Access denied.')]
    public function showResponse(
        int $formId,
        int $responseId,
        TemplateRepository $templateRepo,
        FormSubmitRepository $submitRepo
    ): Response {

        if (!$this->getUser()) {
            $this->addFlash('warning', 'Please log in to continue.');
            return $this->redirectToRoute('app_login');
        }

        // 1) Load the form
        $template = $templateRepo->find($formId);
        if (!$template) {
            throw $this->createNotFoundException("Form #{$formId} not found");
        }

        // 2) Load the single response
        $submit = $submitRepo->find($responseId);
        if (!$submit || $submit->getTemplate()->getId() !== $formId) {
            throw $this->createNotFoundException("Response #{$responseId} not found for form #{$formId}");
        }

        // 3) Render the “view single response” template
        return $this->render('admin/form_single_response.html.twig', [
            'template' => $template,
            'submit'   => $submit,
        ]);
    }



    // ###########################  ADMIN PROFILE  ###################################################

    #[Route('/admin/profile', name: 'admin_profile', methods: ['GET','POST'])]
    #[IsGranted("ROLE_ADMIN", statusCode: 403, message: 'Access denied.')]
    public function profile(
        Request $request,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $passwordHasher,
        CsrfTokenManagerInterface $csrfManager
    ): Response
    {

        if (!$this->getUser()) {
            $this->addFlash('warning', 'Please log in to continue.');
            return $this->redirectToRoute('app_login');
        }

        /** @var User $user */
        $user = $this->getUser();

        // build a simple form model for changing password
        $form = $this->createForm(ChangePasswordType::class, null, [
            'action' => $this->generateUrl('admin_profile'),
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
            return $this->redirectToRoute('admin_profile');
        }

        return $this->render('admin/profile.html.twig', [
            'email' => $user->getEmail(),
            'form'  => $form->createView(),
        ]);
    }





}
