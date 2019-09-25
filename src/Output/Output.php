<?php

namespace Output;

use Comparison\Energy;
use Comparison\Plan;
use Comparison\Price;

class Output
{
    private $outHandle;

    public function __construct($outHandle)
    {
        $this->outHandle = $outHandle;
    }

    public static function toFile(string $file): self
    {
        return new static (fopen('output', 'a'));
    }

    public static function toStdOut()
    {
        return new static (fopen('php://stdout', 'w'));
    }

    public function writePrice(Plan $plan, Price $price)
    {
        $fields = [
            $plan->getSupplier(),
            $plan->getPlanName(),
            $price->getPounds()
        ];

        fputcsv($this->outHandle, $fields);
    }

    public function close()
    {
        fclose($this->outHandle);
    }

    public function writeUsage(Energy $usage)
    {
        fputs($this->outHandle, $usage->round() . "\n");
    }
}
