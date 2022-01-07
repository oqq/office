<?php

declare(strict_types=1);

namespace Oqq\Office\Jira\Tempo;

use Oqq\Office\Jira\Api\IssueKey;
use Oqq\Office\Util\Assertion;

final class Issue
{
    private IssueId $issueId;
    private IssueKey $issueKey;

    public static function fromArray(array $values): self
    {
        Assertion::keyExists($values, 'id');
        Assertion::integer($values['id']);

        Assertion::keyExists($values, 'key');
        Assertion::string($values['key']);

        $issueId = IssueId::fromInt($values['id']);
        $issueKey = IssueKey::fromString($values['key']);

        return new self($issueId, $issueKey);
    }

    public function issueKey(): IssueKey
    {
        return $this->issueKey;
    }

    private function __construct(IssueId $issueId, IssueKey $issueKey)
    {
        $this->issueId = $issueId;
        $this->issueKey = $issueKey;
    }
}
