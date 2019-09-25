<?php

namespace Command;

use Comparison\TotalPlanCost;
use Comparison\Energy;
use Comparison\Price;
use Comparison\Supplier;
use Comparison\VAT;
use Factory\PlanFactory;
use Output\Output;

class PriceCommand
{
    /**
     * @var int
     */
    private $annualUsage;
    /**
     * @var VAT
     */
    private $vat;

    public function __construct(Energy $annualUsage, VAT $vat)
    {
        $this->annualUsage = $annualUsage;
        $this->vat = $vat;
    }

    public function execute(PriceCommandHandler $handler)
    {
        $handler->handle($this->annualUsage, $this->vat);
    }
}
