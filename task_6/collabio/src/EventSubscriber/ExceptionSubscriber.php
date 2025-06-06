<?php

// src/EventSubscriber/ExceptionSubscriber.php
namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class ExceptionSubscriber implements EventSubscriberInterface
{
    private Environment $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $e = $event->getThrowable();

        // Only handle 404’s
        if (!$e instanceof NotFoundHttpException) {
            return;
        }

        // Render your template, but catch any Twig errors…
        try {
            $html = $this->twig->render('error/404.html.twig');
        } catch (LoaderError|SyntaxError|RuntimeError $twigEx) {
            // Fallback content if the template itself is broken
            $html = <<<'HTML'
                        <div style="background:linear-gradient(135deg,#6f42c1,#007bff);min-height:100vh;margin:0;padding:0;display:flex;align-items:center;justify-content:center;font-family:'Quicksand',sans-serif;color:#fff;">
                          <div style="background:rgba(0,0,50,0.7);padding:40px;border-radius:16px;text-align:center;max-width:600px;width:90%;box-shadow:0 8px 24px rgba(0,0,0,0.4);">
                            <h1 style="font-size:3rem;margin:0 0 20px;">404 &ndash; Page Not Found</h1>
                            <p style="font-size:1.2rem;margin:0 0 30px;line-height:1.4;">
                              Sorry, the page you requested could not be found.
                            </p>
                            <a href="{{ path('app_home_index') }}" style="display:inline-block;width:100%;padding:12px 0;border-radius:50px;background-color:#00ffea;color:#000;font-weight:500;text-decoration:none;transition:background-color .2s,transform .2s,box-shadow .2s;">
                              &larr; Back to Home
                            </a>
                          </div>
                        </div>
                    HTML;
        }

        $response = new Response($html, 404);
        $event->setResponse($response);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ExceptionEvent::class => ['onKernelException', 0],
        ];
    }
}
