<?php

declare(strict_types=1);

namespace App\FeeCalculator\Application\Subscriber;

use App\FeeCalculator\Application\Normalizer\ValidationExceptionNormalizer;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\Validator\Exception\ValidationFailedException;

class ValidationExceptionSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly ValidationExceptionNormalizer $violationListNormalizer,
    ) {}

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if (false === ($exception instanceof ValidationFailedException)) {
            return;
        }

        $errors = $this->violationListNormalizer->normalize($exception);

        $event->setResponse(new JsonResponse($errors, 422));
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'kernel.exception' => 'onKernelException',
        ];
    }
}
