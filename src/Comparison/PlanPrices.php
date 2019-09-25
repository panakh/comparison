<?php

namespace Comparison;

class PlanPrices
{
    private $planPrices = [];

    public static function totalCostsForPlans(Energy $consumption, array $plans): self
    {
        $prices = new static();

        $vat  = VAT::percentage(5);

        foreach ($plans as $plan) {
            $prices->addPrice(
                PlanPrice::forConsumptionUnderPlanAndVAT($consumption, $plan, $vat));
        }

        return $prices;
    }

    public function addPrice(PlanPrice $planPrice)
    {
        $this->planPrices[] = $planPrice;
    }

    public function sortAscending(): void
    {
        usort($this->planPrices, function (PlanPrice $priceA, PlanPrice $priceB) {
            return $priceA->getPriceInPence() <=> $priceB->getPriceInPence();
        });
    }

    public function getPlanPrices()
    {
        return $this->planPrices;
    }
}