<?php


namespace Comparison;

final class PlanPrice
{
    /**
     * @var Plan
     */
    private $plan;
    /**
     * @var Price
     */
    private $price;

    public function __construct(Plan $plan, Price $price)
    {
        $this->plan = $plan;
        $this->price = $price;
    }

    public function getPriceInPence(): float
    {
        return $this->price->getPence();
    }

    /**
     * @return Plan
     */
    public function getPlan(): Plan
    {
        return $this->plan;
    }

    /**
     * @return Price
     */
    public function getPrice(): Price
    {
        return $this->price;
    }
}