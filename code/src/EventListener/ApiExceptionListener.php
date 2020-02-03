<?php declare(strict_types=1);

namespace App\EventListener;

use App\Exception\ApiException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

/**
 * Class ApiExceptionListener
 * @package App\EventListener
 */
class ApiExceptionListener
{
    /**
     * @param ExceptionEvent $event
     */
    public function onKernelException(ExceptionEvent $event)
    {
        $e = $event->getThrowable();
        if (!$e instanceof ApiException) {
            return;
        }
        $data = [
            'meta' => 'data_error',
            'title' => $e->getMessage(),
        ];
        $response =  new JsonResponse($data, 400);
        $event->setResponse($response);
    }
}