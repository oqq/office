<?php

declare(strict_types=1);

namespace Oqq\Office\Timeular;

use Oqq\Office\Util\Assert;

final class Activity
{
    private string $id;
    private string $name;
    private string $color;
    private string $spaceId;

    public static function fromArray(array $values): self
    {
        Assert::keyExists($values, 'id');
        Assert::stringNotEmpty($values['id']);

        Assert::keyExists($values, 'name');
        Assert::stringNotEmpty($values['name']);

        Assert::keyExists($values, 'color');
        Assert::stringNotEmpty($values['color']);

        Assert::keyExists($values, 'spaceId');
        Assert::stringNotEmpty($values['spaceId']);

        return new self($values['id'], $values['name'], $values['color'], $values['spaceId']);
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

    public function spaceId(): string
    {
        return $this->spaceId;
    }

    private function __construct(string $id, string $name, string $color, string $spaceId)
    {
        $this->id = $id;
        $this->name = $name;
        $this->color = $color;
        $this->spaceId = $spaceId;
    }
}
