<?php declare(strict_types=1);

namespace App\EventListener;

use App\Exception\ValidationException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

/**
 * Class ValidationExceptionListener
 * @package App\EventListener
 */
class ValidationExceptionListener
{
    /**
     * @param ExceptionEvent $event
     */
    public function onKernelException(ExceptionEvent $event)
    {
        $e = $event->getThrowable();
        if (!$e instanceof ValidationException) {
            return;
        }
        $data = [
            'meta' => 'validation_error',
            'title' => $e->getMessage(),
            'errors' => $e->getValidationErrors()
        ];
        $response =  new JsonResponse($data, 400);
        $event->setResponse($response);
    }
}