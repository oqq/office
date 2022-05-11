<?php

declare(strict_types=1);

namespace Oqq\Office\Service\Banking;

use Oqq\Office\Util\Assertion;

final class Transaction
{
    /** @var non-empty-string */
    private string $date;
    private string $name;
    private string $description;
    private float $amount;

    public static function fromArray(array $payload): self
    {
        Assertion::keyExists($payload, 'date');
        Assertion::stringNotEmpty($payload['date']);

        Assertion::keyExists($payload, 'name');
        Assertion::string($payload['name']);

        Assertion::keyExists($payload, 'description');
        Assertion::string($payload['description']);

        Assertion::keyExists($payload, 'amount');
        Assertion::float($payload['amount']);

        return new self($payload['date'], $payload['name'], $payload['description'], $payload['amount']);
    }

    public function toArray(): array
    {
        return [
            'date' => $this->date,
            'name' => $this->name,
            'description' => $this->description,
            'amount' => $this->amount,
        ];
    }

    /**
     * @return non-empty-string
     */
    public function date(): string
    {
        return $this->date;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function description(): string
    {
        return $this->description;
    }

    public function amount(): float
    {
        return $this->amount;
    }

    /**
     * @param non-empty-string $date
     */
    private function __construct(string $date, string $name, string $description, float $amount)
    {
        $this->date = $date;
        $this->name = $name;
        $this->description = $description;
        $this->amount = $amount;
    }
}
