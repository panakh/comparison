<?php

namespace Command;

use Comparison\PlanName;
use Comparison\Price;
use Comparison\Supplier;
use Comparison\VAT;

class UsageCommand
{
    /**
     * @var int
     */
    private $supplier;
    /**
     * @var string
     */
    private $planName;
    /**
     * @var float
     */
    private $monthlySpend;
    /**
     * @var VAT
     */
    private $vat;

    public function __construct(
        Supplier $supplier,
        PlanName $planName,
        Price $monthlySpend,
        VAT $vat
    ) {
        $this->supplier = $supplier;
        $this->planName = $planName;
        $this->monthlySpend = $monthlySpend;
        $this->vat = $vat;
    }

    public function execute(UsageCommandHandler $handler)
    {
        $handler->handle($this->supplier, $this->planName, $this->monthlySpend, $this->vat);
    }
}
