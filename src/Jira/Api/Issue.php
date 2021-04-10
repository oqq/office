<?php

declare(strict_types=1);

namespace Oqq\Office\Jira\Api;

use Oqq\Office\Jira\Api\IssueKey;
use Oqq\Office\Util\Assert;

final class Issue
{
    private IssueId $issueId;
    private IssueKey $issueKey;

    public static function fromArray(array $values): self
    {
        Assert::keyExists($values, 'id');
        Assert::string($values['id']);

        Assert::keyExists($values, 'key');
        Assert::string($values['key']);

        $issueId = IssueId::fromString($values['id']);
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
