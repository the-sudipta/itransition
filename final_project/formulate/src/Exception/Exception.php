<?php
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
        return [
            KernelEvents::EXCEPTION => ['onKernelException', /*priority*/ 10],
        ];
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $request = $event->getRequest();
        $throwable = $event->getThrowable();

        // 1) Database errors → custom DB page
        if ($throwable instanceof DBALException) {
            $html = $this->twig->render('exception/database_error.html.twig', [
                'errorFile'       => $throwable->getFile(),
                'errorLine'       => $throwable->getLine(),
                'errorMessage'    => $throwable->getMessage(),
                'previousMessage' => $throwable->getPrevious()?->getMessage(),
                'previousLine'    => $throwable->getPrevious()?->getLine(),
            ]);
            $event->setResponse(new Response($html, Response::HTTP_INTERNAL_SERVER_ERROR));
            return;
        }

        // 2) HTTP errors (404, 403, etc.)
        if ($throwable instanceof HttpExceptionInterface) {
            $statusCode = $throwable->getStatusCode();

            // —––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––
            // HANDLE 404:
            // —––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––
            if ($statusCode === Response::HTTP_NOT_FOUND) {
                $html = $this->twig->render('exception/404.html.twig', [
                    'statusCode' => $statusCode,
                    'pathInfo'   => $request->getPathInfo(),
                ]);

                $event->setResponse(new Response($html, $statusCode));
                return; // stop here for 404
            }

            $template   = sprintf('exception/%d.html.twig', $statusCode);

            if ($this->twig->getLoader()->exists($template)) {
                $html = $this->twig->render($template, [
                    'statusCode'   => $statusCode,
                    'errorFile'    => $throwable->getFile(),
                    'errorMessage' => $throwable->getMessage(),
                ]);
                $event->setResponse(new Response($html, $statusCode));
                return;
            }
        }

        // 3) EVERYTHING ELSE → custom 500 page
        $html = $this->twig->render('exception/500.html.twig', [
            'errorFile'       => $throwable->getFile(),
            'errorLine'       => $throwable->getLine(),
            'errorMessage'    => $throwable->getMessage(),
            'previousMessage' => $throwable->getPrevious()?->getMessage(),
            'previousLine'    => $throwable->getPrevious()?->getLine(),
        ]);
        $event->setResponse(new Response($html, Response::HTTP_INTERNAL_SERVER_ERROR));
    }
}
