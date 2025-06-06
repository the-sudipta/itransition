<?php

namespace App\Controller;

use Google_Client;
use Google_Service_Drive;
use Google_Service_Drive_Permission;
use Google_Service_Slides;
use Google_Service_Slides_BatchUpdatePresentationRequest;
use Google_Service_Slides_Presentation;
use Google_Service_Slides_Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home_index')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

//    #[Route('/create', name: 'app_home_create_session')]
//    public function createSession(): Response
//    {
//        return $this->render('home/create_session.html.twig', [
//            'controller_name' => 'HomeController',
//        ]);
//    }

    #[Route('/create', name: 'app_home_create_session')]
    public function createSession(Request $request): Response
    {

        if($request->isMethod('POST')){
            // 1) Retrieve the creator's nickname from the POST request
            $creatorName = trim($request->request->get('nickname', 'Creator'));

            // 2) Initialize Google_Client and authenticate with the service account JSON
            $client = new Google_Client();
            $client->setAuthConfig(
                $this->getParameter('kernel.project_dir') . '/config/google/service-account.json'
            );
            $client->addScope(Google_Service_Slides::PRESENTATIONS);
            $client->addScope(Google_Service_Drive::DRIVE);

            // 3) Use the Slides API to create a new blank presentation
            $slidesService = new Google_Service_Slides($client);
            $presentation = new \Google_Service_Slides_Presentation([
                'title' => "Collabio Session by {$creatorName}"
            ]);
            $presentation = $slidesService->presentations->create($presentation);
            $presentationId = $presentation->getPresentationId();

            // 4) Build the “edit” URL for Google Slides
            $editUrl = "https://docs.google.com/presentation/d/{$presentationId}/edit";

            // 5) Use the Drive API to grant “anyone with link can edit”
            $driveService = new Google_Service_Drive($client);
            $permission = new Google_Service_Drive_Permission();
            $permission->setType('anyone');
            $permission->setRole('writer');            // “writer” = can edit
            $permission->setAllowFileDiscovery(false);  // link-only
            $driveService->permissions->create($presentationId, $permission);

            // 6) Redirect the creator into the Slides editor immediately
//            return new RedirectResponse($editUrl);
            return $this->redirectToRoute('app_embed_slide', [
                'presentationId' => $presentationId,
                'creator'        => $creatorName,
            ]);
        }else{
            return $this->render('home/create_session.html.twig', [
            'controller_name' => 'HomeController',
        ]);
        }

    }

//    #[Route('/join', name: 'app_home_join_session')]
//    public function joinSession(): Response
//    {
//        return $this->render('home/join_session.html.twig', [
//            'controller_name' => 'HomeController',
//        ]);
//    }

    #[Route('/join', name: 'app_home_join_session')]
    /**
     * @Route("/join", name="app_home_join_session", methods={"GET","POST"})
     */
    public function joinSession(Request $request): Response
    {
        if (! $request->isMethod('POST')) {
            // Simple GET → show the join form
            return $this->render('home/join_session.html.twig');
        }

        // 1) Read “nickname” and the user‐supplied “sessionId” (which might be a full URL)
        $collabName       = trim($request->request->get('nickname', 'Collaborator'));
        $presentationInput = trim($request->request->get('presentationId', ''));

        if (! $presentationInput) {
            return $this->redirectToRoute('app_home_index');
        }

        // 2) Normalize the input into a raw presentation ID (ABCDEFGHIJKL)
        $presentationId = $this->normalizeToPresentationId($presentationInput);

        // 3) OPTIONAL: Insert a static “Joined by {collabName}” label onto slide #1
        //    (same as before)
        $this->stampJoinedLabel($presentationId, $collabName);

        // 4) Redirect the user into /embed/{presentationId}?nickname={collabName}
        return $this->redirectToRoute('app_embed_slide', [
            'presentationId' => $presentationId,
            'nickname'       => $collabName,
        ]);
    }

    /**
     * Helper: take input that might be:
     *   • a raw ID (“ABCDEFGHIJKL”)
     *   • a full Google‐Slides URL (“https://docs.google.com/presentation/d/ABCDEFGHIJKL/edit?…”)
     *   • OR even your own “/embed/…” URL (“http://…/embed/ABCDEFGHIJKL?…”)
     * and return just the raw ID (“ABCDEFGHIJKL”).
     */
    private function normalizeToPresentationId(string $input): string
    {
        // 1) If it looks like a complete URL, parse it
        if (filter_var($input, FILTER_VALIDATE_URL)) {
            $parts = parse_url($input);

            // 2) If the host is “docs.google.com” and path contains “/d/…/”
            if (isset($parts['host']) && strpos($parts['host'], 'docs.google.com') !== false) {
                // extract between “/d/” and the next “/”
                if (preg_match('@/presentation/d/([a-zA-Z0-9_-]+)@', $input, $m)) {
                    return $m[1];
                }
            }

            // 3) Otherwise, if this is your own “/embed/…” URL
            //    e.g. “http://127.0.0.1:8000/embed/ABCDEFGHIJKL?nickname=Bob”
            //    then parse path to find “embed/{id}”
            if (isset($parts['path'])) {
                $pathSegments = explode('/', trim($parts['path'], '/'));
                // e.g. [ 'embed', 'ABCDEFGHIJKL' ]
                if (count($pathSegments) >= 2 && $pathSegments[0] === 'embed') {
                    return $pathSegments[1];
                }
            }

            // If we still didn’t get it, fall back to raw input
            return $input;
        }

        // 4) Not a URL → assume they typed the raw ID
        return $input;
    }

    /**
     * OPTIONAL helper: stamp “Joined by {name}” onto slide #1 using Slides API.
     */
    private function stampJoinedLabel(string $presentationId, string $collabName): void
    {
        $client = new Google_Client();
        $client->setAuthConfig(
            $this->getParameter('kernel.project_dir').'/config/google/service-account.json'
        );
        $client->addScope(Google_Service_Slides::PRESENTATIONS);

        $slidesService = new Google_Service_Slides($client);
        try {
            $presentation = $slidesService->presentations->get($presentationId);
        } catch (\Exception $e) {
            // If the deck doesn’t exist / cannot be retrieved, skip stamping.
            return;
        }

        $firstSlideId = $presentation->getSlides()[0]->getObjectId();
        $boxId        = 'JoinedLabel_' . uniqid();
        $requests     = [
            new Google_Service_Slides_Request([
                'createShape' => [
                    'objectId'          => $boxId,
                    'shapeType'         => 'TEXT_BOX',
                    'elementProperties' => [
                        'pageObjectId' => $firstSlideId,
                        'size'         => [
                            'height' => ['magnitude' => 0.4, 'unit' => 'PT'],
                            'width'  => ['magnitude' => 3.0, 'unit' => 'PT']
                        ],
                        'transform'    => [
                            'scaleX'       => 1,
                            'scaleY'       => 1,
                            'translateX'   => 50,
                            'translateY'   => 80,
                            'unit'         => 'PT'
                        ]
                    ]
                ]
            ]),
            new Google_Service_Slides_Request([
                'insertText' => [
                    'objectId'       => $boxId,
                    'insertionIndex' => 0,
                    'text'           => "Joined by {$collabName}"
                ]
            ]),
            new Google_Service_Slides_Request([
                'updateTextStyle' => [
                    'objectId' => $boxId,
                    'style'    => [
                        'fontFamily'      => 'Arial',
                        'fontSize'        => ['magnitude' => 10, 'unit' => 'PT'],
                        'italic'          => true,
                        'foregroundColor' => [
                            'opaqueColor' => [
                                'rgbColor' => ['red' => 0.1, 'green' => 0.1, 'blue' => 0.1]
                            ]
                        ]
                    ],
                    'textRange' => ['type'   => 'ALL'],
                    'fields'    => 'fontFamily,fontSize,italic,foregroundColor'
                ]
            ]),
        ];

        $batchUpdate = new Google_Service_Slides_BatchUpdatePresentationRequest([
            'requests' => $requests
        ]);
        $slidesService->presentations->batchUpdate($presentationId, $batchUpdate);
    }

//    Now i will need CollaborationController for further  uses


    /**
     * Helper: Given a full Slides URL or raw ID, return just the ID.
     */
    private function extractPresentationId(string $editUrl): string
    {
        if (preg_match('@/presentation/d/([a-zA-Z0-9_-]+)@', $editUrl, $matches)) {
            return $matches[1];
        }
        // If it wasn’t a URL, assume they provided the ID itself
        return $editUrl;
    }

    #[Route('/embed/{presentationId}', name: 'app_embed_slide')]
    public function embedSlide(string $presentationId, Request $request): Response
    {
        $creator      = $request->query->get('creator', '');
        $collaborator = $request->query->get('nickname', '');

        $editUrl = "https://docs.google.com/presentation/d/{$presentationId}/edit";

        return $this->render('home/embed_slide.html.twig', [
            'presentationId' => $presentationId,
            'creatorName'    => $creator,
            'collabName'     => $collaborator,
            'editUrl'        => $editUrl,
        ]);
    }




}
