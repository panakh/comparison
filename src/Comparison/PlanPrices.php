<?php

namespace Comparison;

class PlanPrices
{
    private $planPrices = [];

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