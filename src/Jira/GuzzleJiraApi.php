<?php

declare(strict_types=1);

namespace Oqq\Office\Jira;

use DateTimeInterface;
use GuzzleHttp\ClientInterface;
use Oqq\Office\Util\Assert;
use Oqq\Office\Util\Json;
use Psr\Http\Message\ResponseInterface;

final class GuzzleJiraApi implements JiraApi
{
    private ClientInterface $client;

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    public function getIssues(IssueKeys $issueKeys): Issues
    {
        $response = $this->client->request('POST','/rest/api/2/search', [
            'json' => [
                'jql' => \sprintf('key IN (%s)', \implode(', ', $issueKeys->toArray())),
            ],
        ]);

        $result = $this->decodeResponse($response);

        Assert::keyExists($result, 'issues');
        Assert::isArray($result['issues']);

        return Issues::fromArray($result['issues']);
    }

    public function getWorklogs(string $worker, DateTimeInterface $from, DateTimeInterface $to): Worklogs
    {
        $response = $this->client->request('POST','/rest/tempo-timesheets/4/worklogs/search', [
            'json' => [
                'worker' => [
                    $worker,
                ],
                'from' => $from->format('Y-m-d'),
                'to' => $to->format('Y-m-d'),
            ],
        ]);

        $result = $this->decodeResponse($response);

        return Worklogs::fromArray($result);
    }

    public function deleteWorkLog(WorklogId $worklogId): void
    {
        $this->client->request('DELETE', '/rest/tempo-timesheets/4/worklogs/' . $worklogId->toString());
    }

    public function createWorklog(string $worker, string $comment, IssueKey $issueKey, DateTimeInterface $started, int $timeSpentSeconds): void
    {
        $this->client->request('POST','/rest/tempo-timesheets/4/worklogs', [
            'json' => [
                'worker' => $worker,
                'originTaskId' => $issueKey->toString(),
                'started' => $started->format('Y-m-d'),
                'timeSpentSeconds' => $timeSpentSeconds,
                'comment' => $comment,
            ],
        ]);
    }

    private function decodeResponse(ResponseInterface $response): array
    {
        return Json::decode((string) $response->getBody());
    }
}
