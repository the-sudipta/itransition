<?php
// src/Controller/SalesforceController.php
namespace App\Controller;

use App\Repository\SalesforceAccountRepository;
use App\Service\SalesforceClientService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class SalesforceController extends AbstractController
{
    /**
     * Step 1: redirect user into Salesforce’s OAuth flow
     */
    #[Route('/salesforce/connect', name: 'salesforce_connect')]
    public function connect(): RedirectResponse
    {
        // where to send them home after a failure
        $homeRoute = $this->isGranted('ROLE_ADMIN')
            ? 'admin_profile'
            : 'app_user_profile';

        // read from $_SERVER (Dotenv injects here)
        $clientId    = $_SERVER['SALESFORCE_CLIENT_ID']    ?? null;
        $callbackUrl = $_SERVER['SALESFORCE_CALLBACK_URL'] ?? null;

        if (!$clientId || !$callbackUrl) {
            $this->addFlash('danger', 'Salesforce credentials are missing.');
            return $this->redirectToRoute($homeRoute);
        }

        $authorizeUrl = sprintf(
            'https://login.salesforce.com/services/oauth2/authorize'
            . '?response_type=code'
            . '&client_id=%s'
            . '&redirect_uri=%s',
            urlencode($clientId),
            urlencode($callbackUrl)
        );

//        dd($authorizeUrl);

        return $this->redirect($authorizeUrl);
    }

    /**
     * Step 2: Salesforce calls back here with ?code=…
     */
    #[Route('/salesforce/callback', name: 'salesforce_callback')]
    public function callback(Request $request,  SalesforceClientService $salesforceClientService): RedirectResponse
    {
        $homeRoute = $this->isGranted('ROLE_ADMIN')
            ? 'admin_profile'
            : 'app_user_profile';

        $code         = $request->query->get('code', '');
        $clientId     = $_SERVER['SALESFORCE_CLIENT_ID']     ?? null;
        $clientSecret = $_SERVER['SALESFORCE_CLIENT_SECRET'] ?? null;
        $callbackUrl  = $_SERVER['SALESFORCE_CALLBACK_URL']  ?? null;

        if (!$code) {
            $this->addFlash('warning', 'No authorization code returned.');
            return $this->redirectToRoute($homeRoute);
        }

        $http = HttpClient::create();
        $response = $http->request('POST', 'https://login.salesforce.com/services/oauth2/token', [
            'body' => [
                'grant_type'    => 'authorization_code',
                'client_id'     => $clientId,
                'client_secret' => $clientSecret,
                'redirect_uri'  => $callbackUrl,
                'code'          => $code,
            ],
        ]);

        if (200 !== $response->getStatusCode()) {
            $this->addFlash(
                'danger',
                'Salesforce token exchange failed (HTTP ' . $response->getStatusCode() . ').'
            );
            return $this->redirectToRoute($homeRoute);
        }

        $data = $response->toArray();
        // ← here you have $data['access_token'], $data['refresh_token'], $data['instance_url'], etc.
        // TODO: persist them in your database, linked to $this->getUser()


//        dd($data);

        // **hand it off** to your service, along with the current User:
        try {
            /** @var \App\Entity\User $user */
            $user = $this->getUser();
//            dd($user);
            $salesforceClientService->persistAuthData($data, $user);

//            $this->addFlash('success', 'Salesforce account connected successfully!');
        } catch (\Throwable $e) {
            $this->addFlash('danger', 'Could not save Salesforce credentials: '.$e->getMessage());
        }

        $this->addFlash('success', 'Salesforce account connected successfully!');
        return $this->redirectToRoute($homeRoute);
    }


    #[Route('/salesforce/sync', name: 'salesforce_sync')]
    public function sync(
        SalesforceClientService     $sfService,
        SalesforceAccountRepository $sfAccountRepo
    ): RedirectResponse {
        $user = $this->getUser();
        if (!$user) {
            $this->addFlash('error', 'Login required to sync.');
            return $this->redirectToRoute('app_login');
        }

        $acct = $sfAccountRepo->findOneBy(['user' => $user]);
        if (!$acct) {
            $this->addFlash('error', 'Connect Salesforce first.');
            return $this->redirectToRoute('app_login');
        }

        // 1) ask service for payloads for Templates, Comments & Likes
        $payloads = $sfService->prepareAllPayloads();

        // 2) prepare HTTP client and endpoint URL
        $http     = HttpClient::create();
        $base     = rtrim($acct->getInstanceUrl(), '/');
        $ver      = 'v56.0';
        // true upsert by external ID
        $endpoint = sprintf(
            '%s/services/data/%s/composite/sobjects?externalIdField=Original_ID__c',
            $base,
            $ver
        );
        $token    = $acct->getAccessToken();

        $total = 0;

        try {
            foreach ($payloads as $records) {
                if (empty($records)) {
                    continue;
                }

                // Salesforce composite supports max 200 records per batch
                foreach (array_chunk($records, 200) as $batch) {
                    $response = $http->request(
                        'POST',
                        $endpoint,
                        [
                            'headers' => [
                                'Authorization' => "Bearer $token",
                                'Content-Type'  => 'application/json',
                            ],
                            'json' => [
                                'allOrNone' => false,
                                'records'   => $batch,
                            ],
                        ]
                    );

                    // 3) HTTP-level check
                    $status = $response->getStatusCode();
                    if ($status >= 300) {
                        $content = $response->getContent(false);
                        throw new \RuntimeException("HTTP $status: $content");
                    }

                    // 4) Salesforce composite result
                    $body = $response->toArray(false);

                    $body = $response->toArray(false);
//                    dd($body);

                    if (!empty($body['hasErrors'])) {
                        $errs = json_encode($body['results']);
                        throw new \RuntimeException("Salesforce upsert errors: $errs");
                    }

                    // count how many records we sent
                    $total += count($batch);
                }
            }

            $this->addFlash('success', "Synced $total records (Templates, Comments & Likes).");
        } catch (\Throwable $e) {
            $this->addFlash('error', 'Sync failed: ' . $e->getMessage());
        }

        return $this->redirectToRoute('app_user_profile');
    }


}
