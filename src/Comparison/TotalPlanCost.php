<?php

namespace Comparison;

class TotalPlanCost
{
    /**
     * @var Energy
     */
    private $billableUsage;

    /**
     * @var Price
     */
    private $billedAmount;

    private function __construct(Energy $billableUsage, Price $billedAmount)
    {
        $this->billableUsage = $billableUsage;
        $this->billedAmount = $billedAmount;
    }

    public static function forEnergyConsumptionUnderPlanWithVAT(Energy $consumption, Plan $plan, VAT $vat): self
    {
        $totalPlanCost = new static(Energy::billableUsage($consumption), Price::zero());
        $totalPlanCost->calculateCost($plan, $vat);

        return $totalPlanCost;
    }

    private function calculateCost(Plan $plan, VAT $vat)
    {
        /** @var CappedRate $cappedRate */
        foreach ($plan->getCappedRates() as $cappedRate) {
            $this->applyCappedRate($cappedRate);
        }

        if ($this->hasUnbilledUsage()) {
            $this->applyRate($plan->getRate());
        }

        $this->applyStandingCharge($plan->getStandingCharge());
        $this->applyVAT($vat);
    }

    private function applyCappedRate(CappedRate $cappedRate): void
    {
        if ($this->billableUsage === 0 ) {
            return;
        }

        if ($cappedRate->isThresholdLessThan($this->billableUsage)) {
            $this->billableUsage  = $this->billableUsage
                ->deductUsage($cappedRate->getThreshold());
            $this->billedAmount = $this->billedAmount->addPrice($cappedRate->getTotalValue());
        }

        if ($cappedRate->isThresholdGreaterOrEquals($this->billableUsage)) {
            $this->billedAmount = $this->billedAmount->addPrice(
                $cappedRate->getPriceForUsage($this->billableUsage));
            $this->billableUsage = Energy::zero();
        }
    }

    private function hasUnbilledUsage(): bool
    {
        return $this->billableUsage > 0;
    }

    private function applyRate(Rate $rate): void
    {
        $this->billedAmount = $this->billedAmount->addPrice($rate->getPriceForUsage($this->billableUsage));
        $this->billableUsage = Energy::zero();
    }

    private function applyStandingCharge(?Price $standingCharge): void
    {
        if (null === $standingCharge) {
            return;
        }

        $this->billedAmount = $this->billedAmount->addPrice($standingCharge->times(365));
    }

    private function applyVAT(VAT $vat): void
    {
        $this->billedAmount = $this->billedAmount->addVAT($vat->getPercentage());
    }

    public function getBilledAmount(): Price
    {
        return $this->billedAmount;
    }
}
