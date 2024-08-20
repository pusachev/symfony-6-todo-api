<?php

declare(strict_types=1);

namespace App\Builder;

use Symfony\Component\HttpFoundation\JsonResponse;

interface ExceptionResponseBuilderInterface
{
    /**
     * @param \Throwable $exception
     * @return JsonResponse|null
     */
    public function getExceptionResponse(\Throwable $exception): ?JsonResponse;
}