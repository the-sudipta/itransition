<?php
// src/EventSubscriber/ExceptionSubscriber.php
namespace App\Exception;

use Doctrine\DBAL\Exception as DBALException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Twig\Environment;

class Exception implements EventSubscriberInterface
{
    public function __construct(private Environment $twig) {}

    public static function getSubscribedEvents(): array
    {
        // Listen for all uncaught exceptions
        return [
            KernelEvents::EXCEPTION => ['onKernelException', 10],
        ];
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $throwable = $event->getThrowable();

        // 1) Database errors → templates/Exception/database_error.html.twig
        if ($throwable instanceof DBALException) {
            $html = $this->twig->render('exception/database_error.html.twig');
            $event->setResponse(new Response($html, Response::HTTP_INTERNAL_SERVER_ERROR));
            return;
        }

        // 2) HTTP errors (404, 403, etc.)
        if ($throwable instanceof HttpExceptionInterface) {
            $statusCode = $throwable->getStatusCode();
            $template   = sprintf('exception/%s_page_not_found.html.twig', $statusCode);

            if ($this->twig->getLoader()->exists($template)) {
                $html = $this->twig->render($template);
                $event->setResponse(new Response($html, $statusCode));
            }
        }

        // otherwise, let the default TwigBundle handler run
    }
}
