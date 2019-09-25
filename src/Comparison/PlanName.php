<?php

namespace Comparison;

final class PlanName
{
    private $name;

    private function __construct(string $name)
    {
        $this->name = $name;
    }

    public static function name(string $name): self
    {
        return new static($name);
    }

    public function __toString(): string
    {
        return $this->name;
    }

    public function equals(PlanName $planName): bool
    {
        return $this->name === $planName->name;
    }
}
