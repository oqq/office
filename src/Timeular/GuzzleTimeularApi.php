<?php

declare(strict_types=1);

namespace Oqq\Office\Timeular;

use DateTimeInterface;
use GuzzleHttp\ClientInterface;
use Oqq\Office\Util\Assertion;
use Oqq\Office\Util\Json;
use Psr\Http\Message\ResponseInterface;

final class GuzzleTimeularApi implements TimeularApi
{
    private ClientInterface $client;

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    public function getActivities(): Activities
    {
        $response = $this->client->request('GET', '/api/v3/activities');
        $result = $this->decodeResponse($response);

        Assertion::keyExists($result, 'activities');
        Assertion::isArray($result['activities']);

        return Activities::fromArray($result['activities']);
    }

    public function getTimeEntries(DateTimeInterface $start, DateTimeInterface $end): TimeEntries
    {
        $requestUri = \sprintf(
            '/api/v3/time-entries/%s/%s',
            $start->format(TimeularApi::DATETIME_FORMAT),
            $end->format(TimeularApi::DATETIME_FORMAT)
        );

        $response = $this->client->request('GET', $requestUri);
        $result = $this->decodeResponse($response);

        Assertion::keyExists($result, 'timeEntries');
        Assertion::isArray($result['timeEntries']);

        return TimeEntries::fromArray($result['timeEntries']);
    }

    private function decodeResponse(ResponseInterface $response): array
    {
        return Json::decode((string) $response->getBody());
    }
}
