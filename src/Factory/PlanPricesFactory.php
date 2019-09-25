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

            $totalPlanCost = new TotalPlanCost(Energy::billableUsage($consumption), Price::zero());
            $totalPlanCost->calculateCost($plan, $vat);

            $prices->addPrice(new PlanPrice($plan, $totalPlanCost->getBilledAmount()));
        }

        return $prices;
    }
}