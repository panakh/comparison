<?php

namespace Comparison;

class Rate
{
    /**
     * @var Price
     */
    protected $price;

    protected function __construct(Price $price)
    {
        $this->price = $price;
    }

    public static function inPence(float $price): self
    {
        return new static(Price::inPence($price));
    }

    public function getPrice(): Price
    {
        return $this->price;
    }

    public function getPriceForUsage(Energy $billableUsage): Price
    {
        return $this->price->addUsage($billableUsage);
    }
}
