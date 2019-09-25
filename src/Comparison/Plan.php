<?php

namespace Comparison;

class Plan
{
    /**
     * @var Supplier
     */
    private $supplier;
    /**
     * @var PlanName
     */
    private $planName;
    /**
     * @var array
     */
    private $cappedRates;
    /**
     * @var Rate
     */
    private $rate;
    /**
     * @var Price
     */
    private $standingCharge;

    private function __construct(Supplier $name, PlanName $plan, array $cappedRates, Rate $rate)
    {
        $this->supplier = $name;
        $this->planName = $plan;
        $this->cappedRates = $cappedRates;
        $this->rate = $rate;
    }

    public static function fromArray(array $planData): self
    {
        $cappedRates = [];
        $rate = null;

        foreach ($planData['rates'] as $rate) {
            if (!isset($rate['threshold'])) {
                $rate = Rate::inPence($rate['price']);
            } else {
                $cappedRates[] = CappedRate::withPriceUptoThreshold(
                    $rate['price'], $rate['threshold']
                );
            }
        }

        return new static(Supplier::name($planData['supplier'] ?? ''), PlanName::name($planData['plan']), $cappedRates, $rate);
    }

    public function getCappedRates(): array
    {
        return $this->cappedRates;
    }

    public function getRate(): Rate
    {
        return $this->rate;
    }

    public function setStandingCharge(Price $standingCharge)
    {
        $this->standingCharge = $standingCharge;
    }

    public function getStandingCharge(): ?Price
    {
        return $this->standingCharge;
    }

    public function getSupplier(): Supplier
    {
        return $this->supplier;
    }

    public function getPlanName(): PlanName
    {
        return $this->planName;
    }

    public function hasStandingCharge(): bool
    {
        return null !== $this->standingCharge;
    }
}
