<?php

declare(strict_types=1);

namespace Oqq\Office\Timeular;

use Oqq\Office\Util\Assertion;

final class TimeEntry
{
    private string $id;
    private string $activityId;
    private Note $note;
    private Duration $duration;

    public static function fromArray(array $values): self
    {
        Assertion::keyExists($values, 'id');
        Assertion::stringNotEmpty($values['id']);

        Assertion::keyExists($values, 'activityId');
        Assertion::stringNotEmpty($values['activityId']);

        Assertion::keyExists($values, 'note');
        Assertion::isArray($values['note']);

        Assertion::keyExists($values, 'duration');
        Assertion::isArray($values['duration']);

        $note = Note::fromArray($values['note']);
        $duration = Duration::fromArray($values['duration']);

        return new self($values['id'], $values['activityId'], $note, $duration);
    }

    public function activityId(): string
    {
        return $this->activityId;
    }

    public function note(): Note
    {
        return $this->note;
    }

    public function duration(): Duration
    {
        return $this->duration;
    }

    private function __construct(string $id, string $activityId, Note $note, Duration $duration)
    {
        $this->id = $id;
        $this->activityId = $activityId;
        $this->note = $note;
        $this->duration = $duration;
    }
}
