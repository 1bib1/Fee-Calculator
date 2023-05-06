<?php

declare(strict_types=1);

namespace App\FeeCalculator\Infrastructure\Entity;

use App\FeeCalculator\Infrastructure\Repository\BreakPointRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BreakPointRepository::class)]
class BreakPoint
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column(type: Types::BIGINT, options: ['unsigned' => true])]
    private int $id; // could be a UUID with custom generator from package

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private float $fee;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private float $amount;

    public function __construct(
        float $amount,
        float $fee,
    ) {
        $this->amount = $amount;
        $this->fee = $fee;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getFee(): float
    {
        return $this->fee;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }
}
