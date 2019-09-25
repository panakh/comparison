<?php

namespace Comparison;

interface PlanRepositoryInterface
{
    public function findAll(): array;

    public function findPlan(Supplier $supplier, PlanName $planName): Plan;
}