<?php

declare(strict_types=1);

namespace Oqq\Office\Timeular;

use Oqq\Office\Util\Assert;

final class Activity
{
    private string $id;
    private string $name;
    private string $color;

    public static function fromArray(array $values): self
    {
        Assert::keyExists($values, 'id');
        Assert::stringNotEmpty($values['id']);

        Assert::keyExists($values, 'name');
        Assert::stringNotEmpty($values['name']);

        Assert::keyExists($values, 'color');
        Assert::stringNotEmpty($values['color']);

        return new self($values['id'], $values['name'], $values['color']);
    }

    public function id(): string
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function color(): string
    {
        return $this->color;
    }

    private function __construct(string $id, string $name, string $color)
    {
        $this->id = $id;
        $this->name = $name;
        $this->color = $color;
    }
}
