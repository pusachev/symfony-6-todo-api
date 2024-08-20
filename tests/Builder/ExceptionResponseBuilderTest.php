<?php

declare(strict_types=1);

namespace App\Tests\Builder;

use App\Builder\ExceptionResponseBuilder;
use Doctrine\DBAL\Exception as DbalException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class ExceptionResponseBuilderTest extends TestCase
{
    /**
     * @dataProvider getExceptionDataProvider
     *
     * @param object $exception
     * @return void
     */
    public function testGetExceptionResponse(
        object $exception,
        ?JsonResponse $expectedResult
    ): void
    {
        $builder = new ExceptionResponseBuilder();

        $response = $builder->getExceptionResponse($exception);

        $this->assertContains($response?->getStatusCode(), [JsonResponse::HTTP_FORBIDDEN, null]);
        $this->assertContains($response?->getContent(), [$expectedResult?->getContent(), null]);
    }

    /**
     * @return object[]
     */
    public function getExceptionDataProvider(): array
    {
        return [
            'valid exception' => [
                new AccessDeniedHttpException(),
                new JsonResponse(['error' => 'Access denied'], 403)
            ],
            'invalid exception' => [
                new DbalException(),
                null
            ],
            'general exception' => [
                new \Exception(),
                null
            ],
        ];
    }
}
