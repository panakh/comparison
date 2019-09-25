<?php

namespace Comparison;

final class Price
{
    /**
     * @var float
     */
    private $pence;

    private function __construct(float $pence)
    {
        $this->pence = $pence;
    }

    public static function inPounds(float $amountInPounds): self
    {
        return new static($amountInPounds * 100);
    }

    public static function inPence(float $pence): self
    {
        return new static($pence);
    }

    public function getPence(): float
    {
        return $this->pence;
    }

    public static function zero(): self
    {
        return new static(0);
    }

    public function addPence(float $pence): Price
    {
        return new static($this->pence + $pence);
    }

    public function addUsage(Energy $billableUsage): self
    {
        return new static($billableUsage->getKwh() * $this->pence);
    }

    public function addPrice(Price $price): self
    {
        return new static($this->pence + $price->pence);
    }

    public function times(float $multiple): self
    {
        return new static($this->pence * $multiple);
    }

    public function addVAT(float $vatPercentage)
    {
        return new static ($this->pence + ($this->pence * $vatPercentage / 100));
    }

    public function getPounds(): float
    {
        return round($this->pence / 100, 2);
    }

    public function monthlyToAnnual(): self
    {
        return new static ($this->pence * 12);
    }

    public function dailyToAnnual(): self
    {
        return new static($this->pence * 365);
    }

    public function excludeVAT(VAT $vat): self
    {
        return new static($this->pence / (1 + $vat->getPercentage() / 100));
    }

    public function deductAnnualStandingCharge(?Price $standingCharge): self
    {
        if (null === $standingCharge) {
            return $this;
        }

        if ($this->greaterOrEquals($standingCharge->dailyToAnnual())) {
            return new static ($this->pence - $standingCharge->dailyToAnnual()->pence);
        }

        return $this;
    }

    public function greaterOrEquals(Price $price): bool
    {
        return $this->pence >= $price->pence;
    }

    public function deduct(Price $price)
    {
        return new static($this->pence - $price->pence);
    }

    public function greaterThanZero(): bool
    {
        return $this->pence > 0;
    }

}
