<?php

namespace Comparison;

final class VAT
{
    /**
     * @var float
     */
    private $percentage;

    private function __construct(float $percentage)
    {
        $this->percentage = $percentage;
    }

    public static function percentage(float $percentage): self
    {
        return new static($percentage);
    }

    public function getPercentage(): float
    {
        return $this->percentage;
    }
}
