<?php
// src/Controller/SalesforceController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\Routing\Annotation\Route;

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
            ? 'admin_dashboard'
            : 'app_user_index';

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
    public function callback(Request $request): RedirectResponse
    {
        $homeRoute = $this->isGranted('ROLE_ADMIN')
            ? 'admin_dashboard'
            : 'app_user_index';

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

        $this->addFlash('success', 'Salesforce account connected successfully!');
        return $this->redirectToRoute($homeRoute);
    }
}
