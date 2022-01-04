<?php

declare(strict_types=1);

namespace Oqq\Office\Jira;

use DateTimeInterface;
use GuzzleHttp\ClientInterface;
use Oqq\Office\Jira\Api\IssueKeys;
use Oqq\Office\Jira\Api\Issues;
use Oqq\Office\Jira\Tempo\Comment;
use Oqq\Office\Jira\Tempo\IssueKey;
use Oqq\Office\Jira\Tempo\TimeSpentSeconds;
use Oqq\Office\Jira\Tempo\WorklogId;
use Oqq\Office\Jira\Tempo\Worklogs;
use Oqq\Office\Util\Assert;
use Oqq\Office\Util\Json;
use Psr\Http\Message\ResponseInterface;

final class GuzzleJiraApi implements JiraApi
{
    public function __construct(
        private ClientInterface $client
    ) {
    }

    public function getIssues(IssueKeys $issueKeys): Issues
    {
        $response = $this->client->request(
            'POST',
            '/rest/api/2/search',
            [
                'json' => [
                    'jql' => \sprintf('key IN (%s)', \implode(', ', $issueKeys->toArray())),
                ],
            ]
        );

        $result = $this->decodeResponse($response);

        Assert::keyExists($result, 'issues');
        Assert::isArray($result['issues']);

        return Issues::fromArray($result['issues']);
    }

    public function getWorklogs(JiraUser $jiraUser, DateTimeInterface $from, DateTimeInterface $to): Worklogs
    {
        $response = $this->client->request(
            'POST',
            '/rest/tempo-timesheets/4/worklogs/search',
            [
                'json' => [
                    'worker' => [
                        $jiraUser->toString(),
                    ],
                    'from' => $from->format('Y-m-d'),
                    'to' => $to->format('Y-m-d'),
                ],
            ]
        );

        $result = $this->decodeResponse($response);

        return Worklogs::fromArray($result);
    }

    public function deleteWorkLog(WorklogId $worklogId): void
    {
        $this->client->request('DELETE', '/rest/tempo-timesheets/4/worklogs/' . $worklogId->toString());
    }

    public function createWorklog(JiraUser $jiraUser, IssueKey $issueKey, DateTimeInterface $started, TimeSpentSeconds $timeSpent, Comment $comment): void
    {
        $this->client->request(
            'POST',
            '/rest/tempo-timesheets/4/worklogs',
            [
                'json' => [
                    'worker' => $jiraUser->toString(),
                    'originTaskId' => $issueKey->toString(),
                    'started' => $started->format('Y-m-d'),
                    'timeSpentSeconds' => $timeSpent->value(),
                    'comment' => $comment->toString(),
                ],
            ]
        );
    }

    private function decodeResponse(ResponseInterface $response): array
    {
        return Json::decode((string)$response->getBody());
    }
}
