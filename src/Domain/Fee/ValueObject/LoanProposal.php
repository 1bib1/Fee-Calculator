<?php

declare(strict_types=1);

namespace App\FeeCalculator\Domain\Fee\ValueObject;

use Symfony\Component\Validator\Constraints as Assert;

class LoanProposal
{
    #[Assert\NotBlank]
    #[Assert\Type('integer'), Assert\Choice([12, 24])]
    public readonly mixed $term;

    #[Assert\NotBlank]
    #[Assert\Positive, Assert\Range(min: 1000, max: 20000)]
    public readonly mixed $amount;

    public function __construct(mixed $term, mixed $amount) {
        $this->term = $term;
        $this->amount = $amount;
    }
}
