<?php

declare(strict_types=1);

namespace Oqq\Office\Test\Timeular;

use DateTimeImmutable;
use GuzzleHttp\ClientInterface;
use Oqq\Office\Exception\InvalidArgumentException;
use Oqq\Office\Timeular\GuzzleTimeularApi;
use Oqq\Office\Util\DateTime;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

/**
 * @covers \Oqq\Office\Timeular\GuzzleTimeularApi
 */
final class GuzzleTimeularApiTest extends TestCase
{
    use ProphecyTrait;

    /** @var ObjectProphecy<ClientInterface> */
    private ObjectProphecy $client;
    private GuzzleTimeularApi $timeularApi;

    public function setUp(): void
    {
        parent::setUp();

        $this->client = $this->prophesize(ClientInterface::class);
        $this->timeularApi = new GuzzleTimeularApi($this->client->reveal());
    }

    public function testItResolvesActivities(): void
    {
        $responsePayload = [
            'activities' => [
                PayloadExample::activity(),
                PayloadExample::activity(),
            ],
        ];

        $body = $this->prophesize(StreamInterface::class);
        $body->__toString()->willReturn(\json_encode($responsePayload, \JSON_THROW_ON_ERROR));

        $response = $this->prophesize(ResponseInterface::class);
        $response->getBody()->willReturn($body);

        $this->client->request(Argument::cetera())->willReturn($response);

        $activities = $this->timeularApi->getActivities();

        Assert::assertCount(2, $activities);
    }

    /**
     * @dataProvider invalidActivitiesResponseProvider
     */
    public function testItThrowsWithInvalidResponseForActivities(\Exception $expectedException, array $responsePayload): void
    {
        $this->expectExceptionObject($expectedException);

        $response = $this->prophesize(ResponseInterface::class);
        $response->getBody()->willReturn(\json_encode($responsePayload, \JSON_THROW_ON_ERROR));

        $this->client->request(Argument::cetera())->willReturn($response);

        $this->timeularApi->getActivities();
    }

    /**
     * @return iterable<string, array{0: \Exception, 1: array}>
     */
    public function invalidActivitiesResponseProvider(): iterable
    {
        yield 'missing activities key' => [
            new InvalidArgumentException('Expected the key "activities" to exist'),
            [],
        ];

        yield 'invalid activities content' => [
            new InvalidArgumentException('Expected an array. Got: boolean'),
            [
                'activities' => false,
            ],
        ];
    }

    public function testItResolvesTimeEntries(): void
    {
        $responsePayload = [
            'timeEntries' => [
                PayloadExample::timeEntry(),
                PayloadExample::timeEntry(),
            ],
        ];

        $body = $this->prophesize(StreamInterface::class);
        $body->__toString()->willReturn(\json_encode($responsePayload, \JSON_THROW_ON_ERROR));

        $response = $this->prophesize(ResponseInterface::class);
        $response->getBody()->willReturn($body);

        $this->client->request(
            Argument::any(),
            Argument::allOf(
                Argument::containingString('2021-01-01'),
                Argument::containingString('2021-01-31'),
            )
        )->willReturn($response);

        $timeEntries = $this->timeularApi->getTimeEntries(
            DateTime::fromString('2021-01-01', 'Y-m-d'),
            DateTime::fromString('2021-01-31', 'Y-m-d'),
        );

        Assert::assertCount(2, $timeEntries);
    }

    /**
     * @dataProvider invalidTimeEntriesResponseProvider
     */
    public function testItThrowsWithInvalidResponseForTimeEntries(\Exception $expectedException, array $responsePayload): void
    {
        $this->expectExceptionObject($expectedException);

        $response = $this->prophesize(ResponseInterface::class);
        $response->getBody()->willReturn(\json_encode($responsePayload, \JSON_THROW_ON_ERROR));

        $this->client->request(Argument::cetera())->willReturn($response);

        $this->timeularApi->getTimeEntries(
            new DateTimeImmutable(),
            new DateTimeImmutable(),
        );
    }

    /**
     * @return iterable<string, array{0: \Exception, 1: array}>
     */
    public function invalidTimeEntriesResponseProvider(): iterable
    {
        yield 'missing time entries key' => [
            new InvalidArgumentException('Expected the key "timeEntries" to exist'),
            [],
        ];

        yield 'invalid time entries content' => [
            new InvalidArgumentException('Expected an array. Got: boolean'),
            [
                'timeEntries' => false,
            ],
        ];
    }
}
