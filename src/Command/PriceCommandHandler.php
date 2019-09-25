<?php

namespace Command;

use Comparison\PlanPrice;
use Comparison\Energy;
use Comparison\PlanRepositoryInterface;
use Comparison\VAT;
use Factory\PlanPricesFactory;
use Output\Output;

class PriceCommandHandler
{
    /**
     * @var Output
     */
    private $fileOutput;
    /**
     * @var PlanRepositoryInterface
     */
    private $planRepository;
    /**
     * @var PlanPricesFactory
     */
    private $planPricesFactory;

    public function __construct(PlanPricesFactory $planPricesFactory, PlanRepositoryInterface $planRepository, Output $fileOutput)
    {
        $this->fileOutput = $fileOutput;
        $this->planRepository = $planRepository;
        $this->planPricesFactory = $planPricesFactory;
    }

    public function handle(Energy $annualUsage, VAT $vat)
    {
        $prices = $this->planPricesFactory->createPlanPricesForEnergyConsumption(
            $annualUsage, $vat, $this->planRepository->findAll()
        );

        $prices->sortAscending();

        /** @var PlanPrice $price */
        foreach ($prices->getPlanPrices() as $price) {
            $this->fileOutput->writePrice($price->getPlan(), $price->getPrice());
        }
    }
}