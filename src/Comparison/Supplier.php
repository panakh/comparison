<?php

namespace Comparison;

final class Supplier
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

    public function __toString()
    {
        return $this->name;
    }

    public function equals(Supplier $supplier): bool
    {
        return $this->name === $supplier->name;
    }
}
