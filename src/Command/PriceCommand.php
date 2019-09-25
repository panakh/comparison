<?php

namespace Command;

use Comparison\Energy;
use Comparison\VAT;

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
