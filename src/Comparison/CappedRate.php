<?php

namespace Comparison;

class CappedRate extends Rate
{
    /**
     * @var Energy
     */
    private $threshold;

    public function __construct(Price $price, Energy $threshold)
    {
        parent::__construct($price);

        $this->threshold = $threshold;
    }

    public static function withPriceUptoThreshold(float $price, float $threshold): self
    {
        return new static(Price::inPence($price), Energy::inKWH($threshold));
    }

    public function getThreshold(): Energy
    {
        return $this->threshold;
    }

    public function isThresholdLessThan(Energy $billableUsage): bool
    {
        return $billableUsage->getKWH() > $this->threshold->getKWH();
    }

    public function getTotalValue(): Price
    {
        return $this->getPrice()->addUsage($this->threshold);
    }

    public function isThresholdGreaterOrEquals(Energy $billableUsage): bool
    {
        return $billableUsage->isLessOrEqualsThreshold($this->threshold);
    }
}
