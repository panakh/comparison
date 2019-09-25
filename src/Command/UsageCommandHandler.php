<?php

namespace Command;

use Comparison\PlanRepository;
use Comparison\PlanName;
use Comparison\Price;
use Comparison\Supplier;
use Comparison\Usage;
use Comparison\UsageCalculator;
use Comparison\VAT;
use Output\Output;

class UsageCommandHandler
{
    /**
     * @var PlanRepository
     */
    private $planRepository;
    /**
     * @var Output
     */
    private $fileOutput;
    /**
     * @var UsageCalculator
     */
    private $usageCalculator;

    public function __construct(
        UsageCalculator $usageCalculator,
        PlanRepository $planRepository,
        Output $fileOutput
    ) {
        $this->usageCalculator = $usageCalculator;
        $this->planRepository = $planRepository;
        $this->fileOutput = $fileOutput;
    }

    public function handle(Supplier $supplier, PlanName $planName, Price $monthlySpend, VAT $vat)
    {
        $planToApply = $this->planRepository->findPlan($supplier, $planName);

        $annualSpend = $monthlySpend
            ->monthlyToAnnual()
            ->excludeVAT($vat)
            ->deductAnnualStandingCharge($planToApply->getStandingCharge());

        $usage = $this->usageCalculator->createUsageForAnnualSpendWithPlan(
            $annualSpend, $planToApply);

        $this->fileOutput->writeUsage($usage);
    }
}