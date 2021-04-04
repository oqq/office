<?php

declare(strict_types=1);

namespace Oqq\Office\Jira;

use Oqq\Office\Util\Assert;

final class Issue
{
    private IssueKey $issueKey;

    public static function fromArray(array $values): self
    {
        Assert::keyExists($values, 'key');
        Assert::string($values['key']);

        $issueKey = IssueKey::fromString($values['key']);

        return new self($issueKey);
    }

    public function issueKey(): IssueKey
    {
        return $this->issueKey;
    }

    private function __construct(IssueKey $issueKey)
    {
        $this->issueKey = $issueKey;
    }
}
