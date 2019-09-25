<?php

namespace Comparison;

use Factory\PlanFactory;
use RuntimeException;

class PlanRepository
{
    /**
     * @var PlanFactory
     */
    private $planFactory;
    /**
     * @var array
     */
    private $plans;

    public function __construct(array $plans)
    {
        $this->plans = $plans;
    }

    public static function fromFile(PlanFactory $planFactory, string $file): self
    {
        $plans = $planFactory->createFromJSONFile($file);

        return new static($plans);
    }

    public function findPlan(Supplier $supplier, PlanName $planName): Plan
    {
        /** @var Plan $plan */
        foreach ($this->plans as $plan) {

            if (
                $plan->getSupplier()->equals($supplier) &&
                $plan->getPlanName()->equals($planName)
            ) {
                return $plan;
            }
        }

        throw new RuntimeException('Plan not found');
    }

    public function findAll(): array
    {
        return $this->plans;
    }
}