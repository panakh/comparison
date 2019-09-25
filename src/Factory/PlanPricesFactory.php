<?php

namespace Factory;

use Comparison\CappedRate;
use Comparison\Energy;
use Comparison\Plan;
use Comparison\PlanPrice;
use Comparison\PlanPrices;
use Comparison\TotalPlanCost;
use Comparison\VAT;

class PlanPricesFactory
{
    public function createPlanPricesForEnergyConsumption(Energy $consumption, VAT $vat,  array $plans): PlanPrices
    {
        $prices = new PlanPrices();

        foreach ($plans as $plan) {
            $prices->addPrice($this->createPlanPriceForConsumptionUnderPlanAndVAT($consumption, $plan, $vat));
        }

        return $prices;
    }

    private function createPlanPriceForConsumptionUnderPlanAndVAT(Energy $consumption, Plan $plan, VAT $vat)
    {
        $totalPlanCost = TotalPlanCost::forConsumption($consumption);

        /** @var CappedRate $cappedRate */
        foreach ($plan->getCappedRates() as $cappedRate) {
            $totalPlanCost->applyCappedRate($cappedRate);
        }

        if ($totalPlanCost->hasUnbilledUsage()) {
            $totalPlanCost->applyRate($plan->getRate());
        }

        $totalPlanCost->applyStandingCharge($plan->getStandingCharge());

        $totalPlanCost->applyVAT($vat);

        return new PlanPrice(
            $plan,
            $totalPlanCost->getBilledAmount()
        );
    }
}