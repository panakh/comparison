<?php

namespace Factory;

use Comparison\Plan;
use Comparison\Price;

class PlanFactory
{
    public function createFromJSONFile(string $filePath): array
    {
        $data = json_decode(file_get_contents($filePath), true);
        $plans = [];

        foreach ($data as $planData) {
            $plan = Plan::fromArray($planData);
            if (isset($planData['standing_charge'])) {
                $plan->setStandingCharge(Price::inPence($planData['standing_charge']));
            }
            $plans[] = $plan;
        }

        return $plans;
    }
}