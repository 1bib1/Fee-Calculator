<?php

declare(strict_types=1);

namespace App\FeeCalculator\Tests\Unit\Aggregate\Service;

use App\FeeCalculator\Aggregate\Fee\Calculator\FeeCalculator;
use App\FeeCalculator\Aggregate\Fee\Service\FeeCalculationService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class FeeCalculationServiceTest extends TestCase
{
    protected FeeCalculationService $feeCalculationService;
    protected ValidatorInterface $validator;
    protected FeeCalculator $feeCalculator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->validator = $this->createMock(ValidatorInterface::class);
        $this->feeCalculator = $this->createMock(FeeCalculator::class);
        $this->feeCalculationService = new FeeCalculationService(
            $this->validator,
            $this->feeCalculator,
        );
    }

    public function testExceptionIsThrownWhenViolationListIsNotEmpty(): void
    {
        $this->validator
            ->expects(self::once())
            ->method('validate')
            ->willReturn(new ConstraintViolationList([
                new ConstraintViolation(...['error', null, [], '',  '', '']),
            ]));

        $this->feeCalculator->expects(self::never())->method('calculate');
        $this->expectException(ValidationFailedException::class);

        $this->feeCalculationService->calculateFromRequest(
            new Request(content: json_encode([]))
        );
    }

    public function testExceptionIsNotThrownWhenViolationListIsEmpty(): void
    {
        $this->validator
            ->expects(self::once())
            ->method('validate')
            ->willReturn(new ConstraintViolationList([]));

        $this->feeCalculator
            ->expects(self::once())
            ->method('calculate')
            ->willReturn(125.00);

        $this->feeCalculationService->calculateFromRequest(
            new Request(content: json_encode([
                'term' => 24,
                'amount' => 10000,
            ]))
        );
    }
}
