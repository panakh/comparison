<?php

namespace Comparison;

final class Energy
{
    /**
     * @var int
     */
    private $kwh;

    private function __construct(float $kwh)
    {
        $this->kwh = $kwh;
    }

    public static function inKWH(float $annualUsage): self
    {
        return new static($annualUsage);
    }

    public static function zero(): self
    {
        return new static(0);
    }

    public static function billableUsage(Energy $consumption): self
    {
        return new static($consumption->getKwh());
    }

    public function getKwh(): float
    {
        return $this->kwh;
    }

    public function deductUsage(Energy $billableUsage): self
    {
        return new static($this->kwh -= $billableUsage->kwh);
    }

    public function isLessOrEqualsThreshold(Energy $threshold): bool
    {
        return $this->kwh <= $threshold->kwh;
    }

    public static function forAnnualSpendWithRate(Price $annualSpend, Price $pricePerKWH): self
    {
        return new static($annualSpend->getPence() /  $pricePerKWH->getPence());
    }
    
    public function round(): float
    {
        return round($this->kwh, 0);
    }

    public function addUsage(Energy $usage): self
    {
        return new static($this->kwh + $usage->getKWH());
    }
}
