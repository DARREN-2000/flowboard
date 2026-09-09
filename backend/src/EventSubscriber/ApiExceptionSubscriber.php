<?php
declare(strict_types=1);

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class ApiExceptionSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException',
        ];
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $e = $event->getThrowable();

        $statusCode = 500;
        $message = 'An unexpected error occurred.';

        if ($e instanceof HttpExceptionInterface) {
            $statusCode = $e->getStatusCode();
            $message = $e->getMessage();
        } elseif ($e instanceof AccessDeniedException) {
            $statusCode = 403;
            $message = 'Access Denied.';
        }

        $response = new JsonResponse([
            'error' => [
                'code' => $statusCode,
                'message' => $message,
            ]
        ], $statusCode);

        $event->setResponse($response);
    }
}
