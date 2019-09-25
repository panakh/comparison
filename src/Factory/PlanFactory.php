<?php

namespace Factory;

use Comparison\CappedRate;
use Comparison\Plan;
use Comparison\PlanName;
use Comparison\Price;
use Comparison\Rate;
use Comparison\Supplier;

class PlanFactory
{
    public function createFromJSONFile(string $filePath): array
    {
        $data = json_decode(file_get_contents($filePath), true);
        $plans = [];

        foreach ($data as $planData) {
            $plan = $this->createFromArray($planData);
            $plans[] = $plan;
        }

        return $plans;
    }

    private function createFromArray($planData)
    {
        $cappedRates = [];
        $rate = null;

        foreach ($planData['rates'] as $rate) {
            if (!isset($rate['threshold'])) {
                $rate = Rate::inPence($rate['price'] ?? 0);
            } else {
                $cappedRates[] = CappedRate::withPriceUptoThreshold(
                    $rate['price'] ?? 0, $rate['threshold']
                );
            }
        }

        $plan = new Plan(
            Supplier::name($planData['supplier'] ?? ''),
            PlanName::name($planData['plan'] ?? ''),
            $cappedRates,
            $rate
        );

        if (isset($planData['standing_charge'])) {
            $plan->setStandingCharge(Price::inPence($planData['standing_charge']));
        }

        return $plan;
    }
}