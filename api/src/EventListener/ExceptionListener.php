<?php

namespace App\EventListener;

use App\Exception\ApiException;
use App\Factory\NormalizerFactory;
use App\Http\ApiResponse;
use Exception;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Throwable;

class ExceptionListener
{
    private NormalizerFactory $normalizerFactory;

    /**
     * ExceptionListener constructor
     *
     * @param NormalizerFactory $normalizerFactory
     */
    public function __construct(NormalizerFactory $normalizerFactory)
    {
        $this->normalizerFactory = $normalizerFactory;
    }

    /**
     * Kernel exception listening function
     *
     * @param ExceptionEvent $event
     * @throws ExceptionInterface
     */
    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();
        $request = $event->getRequest();

        if (in_array('application/json', $request->getAcceptableContentTypes())) {
            $response = $this->createApiResponse($exception);
            $event->setResponse($response);
        }
    }

    /**
     * Function creating ApiResponse
     *
     * @param Throwable $exception
     *
     * @return ApiResponse
     * @throws ExceptionInterface
     */
    private function createApiResponse(Throwable $exception): ApiResponse
    {
        $normalizer = $this->normalizerFactory->getNormalizer($exception);
        $statusCode = $exception instanceof HttpExceptionInterface ? $exception->getStatusCode() : Response::HTTP_INTERNAL_SERVER_ERROR;
        $data = $exception instanceof ApiException ? $exception->getData() : null; //TODO:

        try {
            $errors = $normalizer ? $normalizer->normalize($exception) : [];
        } catch (Exception $e) {
            $errors = [];
        }

        return new ApiResponse($exception->getMessage(), $data, $errors, $statusCode);
    }
}