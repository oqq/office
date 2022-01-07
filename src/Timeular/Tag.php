<?php

declare(strict_types=1);

namespace Oqq\Office\Timeular;

use Oqq\Office\Util\Assertion;

final class Tag
{
    private int $id;
    private string $label;

    public static function fromArray(array $values): self
    {
        Assertion::keyExists($values, 'id');
        Assertion::positiveInteger($values['id']);

        Assertion::keyExists($values, 'label');
        Assertion::stringNotEmpty($values['label']);

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
