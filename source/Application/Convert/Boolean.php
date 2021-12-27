<?php

declare(strict_types=1);

namespace UUP\Application\Convert;

use InvalidArgumentException;

class Boolean
{
    private bool $value;

    public function __construct($value = false)
    {
        $this->setValue($value);
    }

    public function getValue(): bool
    {
        return $this->value;
    }

    public function setValue($value): void
    {
        if (is_bool($value)) {
            $this->value = $value;
        } elseif (is_int($value)) {
            $this->value = !($value == 0);
        } elseif (is_float($value)) {
            $this->value = !($value == 0);
        } elseif (is_string($value)) {
            $this->value = $this->getFromString($value);
        } elseif (is_null($value)) {
            $this->value = false;
        } else {
            throw new InvalidArgumentException("Expected bool, numeric or string value");
        }
    }

    public static function convert($value): bool
    {
        return (new Boolean($value))->getValue();
    }

    public function toString(): string
    {
        return $this->value ? "true": "false";
    }

    private function getFromString(string $value): bool
    {
        switch (strtolower($value)) {
            case "true":
            case "yes":
            case "on":
            case "1":
                return true;
            case "false":
            case "no":
            case "off":
            case "0":
                return false;
            default:
                throw new InvalidArgumentException("Unrecognized string value '$value' for boolean conversion");
        }
    }
}
