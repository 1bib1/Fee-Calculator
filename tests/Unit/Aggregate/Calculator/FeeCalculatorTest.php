<?php

declare(strict_types=1);

namespace App\FeeCalculator\Tests\Unit\Aggregate\Calculator;

use App\FeeCalculator\Aggregate\Fee\Calculator\FeeCalculator;
use App\FeeCalculator\Domain\BreakPoint\Interface\BreakPointServiceInterface;
use App\FeeCalculator\Domain\Fee\ValueObject\LoanProposal;
use App\FeeCalculator\Infrastructure\Entity\BreakPoint;
use Exception;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionMethod;

class FeeCalculatorTest extends TestCase
{
    protected FeeCalculator $feeCalculator;
    protected BreakPointServiceInterface $breakPointService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->breakPointService = $this->createMock(BreakPointServiceInterface::class);
        $this->feeCalculator = new FeeCalculator($this->breakPointService);
    }

    public function testErrorIsThrownWhenThereIsNoBreakingPointFound(): void
    {
        $this->breakPointService
            ->expects(self::once())
            ->method('getBreakPoints')
            ->willReturn([]);

        $this->expectException(Exception::class);

        $method = $this->getAccessibleMethod('getBreakPoints');
        $method->invokeArgs($this->feeCalculator, [ 12 ]);
    }

    public function testErrorIsThrownWhenWeCantFindMinMaxBreakpoints(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $method = $this->getAccessibleMethod('findBreakPoints');

        $method->invokeArgs($this->feeCalculator, [
            [
                $this->createBreakPoint(1111, 0.1),
            ],
            2222]
        );
    }

    public function testMinMaxValuesCanBeChosen(): void
    {
        $method = $this->getAccessibleMethod('findBreakPoints');
        $expectedMin = $this->createBreakPoint(1111.0, 0.1);
        $expectedMax = $this->createBreakPoint(3333.0, 0.1);
        $stray = $this->createBreakPoint(99999.0, 0.1);

        [$min, $max] = $method->invokeArgs($this->feeCalculator, [
            [
                $stray,
                $expectedMin,
                $expectedMax,
            ],
            2222.0,
        ]);

        self::assertSame($expectedMin, $min);
        self::assertSame($expectedMax, $max);
        // we want the closest breakpoint to be returned, so...
        self::assertNotSame($expectedMax, $stray);
    }

    /**
     * @dataProvider provideForCalculation
     */
    public function testCalculation(float $loanAmount, float $expected): void
    {
        $min = $this->createBreakPoint(1111.0, 0.1);
        $max = $this->createBreakPoint(3333.0, 0.1);

        $this->breakPointService
            ->expects(self::once())
            ->method('getBreakPoints')
            ->willReturn([$min, $max]);

        $calculatedFee = $this->feeCalculator->calculate(new LoanProposal(12, $loanAmount));

        self::assertSame($expected, $calculatedFee);
    }

    private function getAccessibleMethod(string $methodName): ReflectionMethod
    {
        $reflection = new ReflectionClass(FeeCalculator::class);
        return $reflection->getMethod($methodName);
    }

    private function createBreakPoint(float $amount, float $fee): BreakPoint
    {
        return new BreakPoint($amount, $fee);
    }

    public static function provideForCalculation(): array
    {
        return [
            [ 2222.3, 225.0 ],
            [ 2355.33, 240.0 ],
            [ 2500.0, 250.0 ],
        ];
    }
}
