<?php

namespace Factory;


use Comparison\Energy;
use Comparison\Plan;
use Comparison\PlanPrice;
use Comparison\PlanPrices;
use Comparison\Price;
use Comparison\TotalPlanCost;
use Comparison\VAT;

class PlanPricesFactory
{
    public function createPlanPricesForEnergyConsumption(Energy $consumption, VAT $vat,  array $plans): PlanPrices
    {
        $prices = new PlanPrices();

        foreach ($plans as $plan) {
            $totalPlanCost = TotalPlanCost::forEnergyConsumptionUnderPlanWithVAT($consumption, $plan, $vat);
            $prices->addPrice(new PlanPrice($plan, $totalPlanCost->getBilledAmount()));
        }

        return $prices;
    }
}