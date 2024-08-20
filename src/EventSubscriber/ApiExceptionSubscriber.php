<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Builder\ExceptionResponseBuilderInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;
class ApiExceptionSubscriber implements EventSubscriberInterface
{
    /**
     * @param ExceptionResponseBuilderInterface $exceptionResponseBuilder
     * @param LoggerInterface $logger
     */
    public function __construct(
        private readonly ExceptionResponseBuilderInterface $exceptionResponseBuilder,
        private readonly LoggerInterface $logger,
    ) {
    }

    /**
     * @param ExceptionEvent $event
     *
     * @return void
     */
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        $response  = $this->exceptionResponseBuilder->getExceptionResponse($exception);
        if(null !== $response){
            $this->logger->error($exception->getMessage(), ['exception' => $exception]);
            $event->setResponse($response);
        }
    }

    /** {@inheritDoc} */
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => ['onKernelException']
        ];
    }
}
