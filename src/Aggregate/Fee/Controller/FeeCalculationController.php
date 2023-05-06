<?php

declare(strict_types=1);

namespace App\FeeCalculator\Aggregate\Fee\Controller;

use App\FeeCalculator\Domain\Fee\Interface\FeeCalculationServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class FeeCalculationController extends AbstractController
{
    public function __construct(
        protected readonly FeeCalculationServiceInterface $feeCalculationService,
    ) {}

    #[Route('api/fee/calculate', name: 'fee.calculate', methods: [Request::METHOD_POST])]
    public function __invoke(Request $request): JsonResponse
    {
        return $this->json([
            'fee' => $this->feeCalculationService->calculateFromRequest($request),
        ]);
    }
}
