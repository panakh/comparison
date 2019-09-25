<?php

namespace Comparison;

class UsageCalculator
{
    public function createUsageForAnnualSpendWithPlan(Price $annualSpend, Plan $planToApply): Energy
    {
        $usage = Energy::zero();

        /** @var CappedRate $cappedRate */
        foreach ($planToApply->getCappedRates() as $cappedRate) {

            if ($annualSpend->greaterOrEquals($cappedRate->getTotalValue())) {
                $annualSpend = $annualSpend->deduct($cappedRate->getTotalValue());
                $usage = $usage->addUsage($cappedRate->getThreshold());
            } else {
                $usage = $usage->addUsage(
                    Energy::forAnnualSpendWithRate(
                        $annualSpend, $cappedRate->getPrice()));
                $annualSpend = Price::zero();
                break;
            }
        }

        if ($annualSpend->greaterThanZero()) {
            $usage = $usage->addUsage(
                Energy::forAnnualSpendWithRate(
                    $annualSpend, $planToApply->getRate()->getPrice()));
        }

        return $usage;
    }
}