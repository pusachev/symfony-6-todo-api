<?php

declare(strict_types=1);

namespace App\Builder;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class ExceptionResponseBuilder implements ExceptionResponseBuilderInterface
{
    /** {@inheritDoc} */
    public function getExceptionResponse(\Throwable $exception): ?JsonResponse
    {
        return match (get_class($exception)) {
            AccessDeniedHttpException::class => new JsonResponse(['error' => 'Access denied'], 403),
            default => null
        };
    }
}
