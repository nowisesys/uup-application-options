<?php

declare(strict_types=1);

namespace UUP\Application\Options;

abstract class ApplicationOptionsBase implements ApplicationOptionsInterface
{
    private array $options = [];

    public function hasOptions(): bool
    {
        return !empty($this->options);
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    protected function setOptions(array $options): void
    {
        $this->options = $options;
    }

    public function addOption(string $name, $value): void
    {
        if ($this->hasOption($name)) {
            $this->options[$name][] = $value;
        } else {
            $this->options[$name] = [$value];
        }
    }

    public function hasOption(string $name): bool
    {
        return array_key_exists($name, $this->options);
    }

    public function getOption(string $name, $default = null)
    {
        return $this->options[$name] ?? $default;
    }

    public function setOption(string $name, $value): void
    {
        $this->options[$name] = $value;
    }

    public function getBoolean(string $name, bool $default = false): bool
    {
        return boolval($this->getOption($name, $default));
    }

    public function getFloat(string $name, float $default = 0.0): float
    {
        return floatval($this->getOption($name, $default));
    }

    public function getInteger(string $name, int $default = 0): int
    {
        return intval($this->getOption($name, $default));
    }

    public function getString(string $name, string $default = ""): string
    {
        return strval($this->getOption($name, $default));
    }

    public function getArray(string $name, array $default = []): array
    {
        return $this->options[$name] ?? $default;
    }

    public function getObject(string $name)
    {
        return $this->getOption($name, null);
    }

    public function setObject(string $name, object $object)
    {
        $this->options[$name] = $object;
    }

    public function isMissing(string $name): bool
    {
        return !$this->hasOption($name);
    }

    public function isObject(string $name): bool
    {
        return is_object($this->options[$name]);
    }
}
