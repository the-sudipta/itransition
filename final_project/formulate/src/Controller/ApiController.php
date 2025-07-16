<?php

namespace App\Controller;

use App\Entity\ApiToken;
use App\Repository\ApiTokenRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ApiController extends AbstractController
{
    #[Route('/api', name: 'app_api')]
    public function index(): Response
    {
        return $this->render('api/index.html.twig', [
            'controller_name' => 'ApiController',
        ]);
    }

    /**
     * Generate or refresh this user’s API token
     */
    #[Route('/profile/token/generate', name: 'api_token_generate', methods: ['POST'])]
    public function generateToken(EntityManagerInterface $em): Response
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        if (!$user) {
            throw $this->createAccessDeniedException('You must be logged in.');
        }

        // new 64‑char hex token
        $newToken = bin2hex(random_bytes(32));

        if ($existing = $user->getApiToken()) {
            $existing->setToken($newToken)->setCreatedAt(new \DateTime());

        } else {
            $apiToken = new ApiToken();
            $apiToken
                ->setToken($newToken)
                ->setUser($user)
                ->setCreatedAt(new \DateTime())
            ;
            $user->setApiToken($apiToken);
        }

        $em->persist($user);
        $em->flush();

        $this->addFlash('success', 'Your API token has been generated.');
        return $this->redirectToRoute('app_user_profile');
    }

    /**
     * API endpoint for Formalytics to fetch all the user’s data
     */
    #[Route('/api/formalytics', name: 'formalytics_api', methods: ['GET'])]
    public function formalyticsApi(Request $request, ApiTokenRepository $repo): JsonResponse
    {
        $token = $request->query->get('token');
        if (!$token) {
            return new JsonResponse(['error' => 'Missing token'], Response::HTTP_BAD_REQUEST);
        }

        $apiToken = $repo->findOneBy(['token' => $token]);
        if (!$apiToken) {
            return new JsonResponse(['error' => 'Invalid token'], Response::HTTP_UNAUTHORIZED);
        }

        $user = $apiToken->getUser();
        $payload = ['templates' => []];

        foreach ($user->getTemplates() as $tpl) {
            $tplData = [
                'id'                => $tpl->getId(),
                'topic'             => $tpl->getTopic(),
                'title'             => $tpl->getTitle(),
                'description'       => $tpl->getDescription(),
                'image'             => $tpl->getImage(),
                'is_public'         => (bool)$tpl->isPublic(),
                'version'           => $tpl->getVersion(),
                'created_at'        => $tpl->getCreatedAt()->format(\DateTime::ATOM),
                'last_updated_at'   => $tpl->getLastUpdatedAt()->format(\DateTime::ATOM),
                'questions'         => [],
                'likes'             => [],
                'comments'          => [],
            ];

            // questions
            foreach ($tpl->getQuestions() as $q) {
                $tplData['questions'][] = [
                    'id'            => $q->getId(),
                    'type'          => $q->getType(),
                    'title'         => $q->getTitle(),
                    'description'   => $q->getDescription(),
                    'position'      => $q->getPosition(),
                ];
            }

            // likes
            foreach ($tpl->getLikes() as $like) {
                $tplData['likes'][] = [
                    'user_id' => $like->getUser()->getId(),
                ];
            }

            // comments
            foreach ($tpl->getComments() as $c) {
                $tplData['comments'][] = [
                    'user_id'    => $c->getUser()->getId(),
                    'content'    => $c->getContent(),
                    'created_at' => $c->getCreatedAt()->format(\DateTime::ATOM),
                ];
            }

            $payload['templates'][] = $tplData;
        }

        return new JsonResponse($payload);
    }

}
