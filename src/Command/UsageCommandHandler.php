<?php

namespace Command;

use Comparison\PlanName;
use Comparison\PlanRepositoryInterface;
use Comparison\Price;
use Comparison\Supplier;
use Comparison\UsageCalculator;
use Comparison\VAT;
use Output\Output;

class UsageCommandHandler
{
    /**
     * @var PlanRepositoryInterface
     */
    private $planRepository;
    /**
     * @var Output
     */
    private $output;
    /**
     * @var UsageCalculator
     */
    private $usageCalculator;

    public function __construct(
        UsageCalculator $usageCalculator,
        PlanRepositoryInterface $planRepository,
        Output $output
    ) {
        $this->usageCalculator = $usageCalculator;
        $this->planRepository = $planRepository;
        $this->output = $output;
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

        $this->output->writeUsage($usage);
    }
}