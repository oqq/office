<?php

declare(strict_types=1);

namespace Oqq\Office\Timeular;

use Oqq\Office\Util\Assert;

final class Tag
{
    private int $id;
    private string $label;

    public static function fromArray(array $values): self
    {
        Assert::keyExists($values, 'id');
        Assert::positiveInteger($values['id']);

        Assert::keyExists($values, 'label');
        Assert::stringNotEmpty($values['label']);

        return new self($values['id'], $values['label']);
    }

    public function id(): int
    {
        return $this->id;
    }

    public function label(): string
    {
        return $this->label;
    }

    private function __construct(int $id, string $label)
    {
        $this->id = $id;
        $this->label = $label;
    }
}
