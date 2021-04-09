<?php

declare(strict_types=1);

namespace Oqq\Office\Test\Application;

use Oqq\Office\Application\LabelIssueKeyResolver;
use Oqq\Office\Exception\RuntimeException;
use Oqq\Office\Test\Timeular\PayloadExample as TimeularPayloadExample;
use Oqq\Office\Timeular\TimeEntry;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Oqq\Office\Application\LabelIssueKeyResolver
 */
final class LabelIssueKeyResolverTest extends TestCase
{
    private LabelIssueKeyResolver $issueKeyResolver;

    protected function setUp(): void
    {
        parent::setUp();

        $this->issueKeyResolver = new LabelIssueKeyResolver();
    }

    /**
     * @dataProvider matchingExamplesProvider
     */
    public function testItFindsIssueKey(string $expectedIssueKey, array $timeEntryValuesExample): void
    {
        $timeEntry = TimeEntry::fromArray($timeEntryValuesExample);
        $issueKey = $this->issueKeyResolver->findIssueKeyFromTimeEntry($timeEntry);

        Assert::assertNotNull($issueKey);
        Assert::assertSame($expectedIssueKey, $issueKey->toString());
    }

    /**
     * @return iterable<string, array{0: string, 1: array}>
     */
    public function matchingExamplesProvider(): iterable
    {
        $timeEntry = TimeularPayloadExample::timeEntry();
        $tag = TimeularPayloadExample::tag();

        yield 'first label is match' => [
            'TEST-123',
            \array_replace_recursive($timeEntry, [
                'note' => [
                    'tags' => [
                        \array_replace($tag, ['label' => 'TEST-123']),
                        \array_replace($tag, ['label' => 'some other label']),
                    ],
                ],
            ]),
        ];

        yield 'second label is match' => [
            'TEST-123',
            \array_replace_recursive($timeEntry, [
                'note' => [
                    'tags' => [
                        \array_replace($tag, ['label' => 'some other label']),
                        \array_replace($tag, ['label' => 'TEST-123']),
                        \array_replace($tag, ['label' => 'some other label']),
                    ],
                ],
            ]),
        ];
    }

    /**
     * @dataProvider notMatchingExamplesProvider
     */
    public function testItResolvesWithNullIfIssueKeyWasNotFound(array $timeEntryValuesExample): void
    {
        $timeEntry = TimeEntry::fromArray($timeEntryValuesExample);
        $issueKey = $this->issueKeyResolver->findIssueKeyFromTimeEntry($timeEntry);

        Assert::assertNull($issueKey);
    }

    /**
     * @return iterable<string, array{0: array}>
     */
    public function notMatchingExamplesProvider(): iterable
    {
        $timeEntry = TimeularPayloadExample::timeEntry();
        $tag = TimeularPayloadExample::tag();

        yield 'one label' => [
            \array_replace_recursive($timeEntry, [
                'note' => [
                    'tags' => [
                        \array_replace($tag, ['label' => 'some label']),
                    ],
                ],
            ]),
        ];

        yield 'two labels' => [
            \array_replace_recursive($timeEntry, [
                'note' => [
                    'tags' => [
                        \array_replace($tag, ['label' => 'some label']),
                        \array_replace($tag, ['label' => 'some other label']),
                    ],
                ],
            ]),
        ];
    }

    /**
     * @dataProvider multipleMatchingExamplesProvider
     */
    public function testItThrowsIfMoreThanOneIssueKeyWasFound(array $timeEntryValuesExample): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Have found more than one issue key in time entry');

        $timeEntry = TimeEntry::fromArray($timeEntryValuesExample);
        $this->issueKeyResolver->findIssueKeyFromTimeEntry($timeEntry);
    }

    /**
     * @return iterable<string, array{0: array}>
     */
    public function multipleMatchingExamplesProvider(): iterable
    {
        $timeEntry = TimeularPayloadExample::timeEntry();
        $tag = TimeularPayloadExample::tag();

        yield 'second label' => [
            \array_replace_recursive($timeEntry, [
                'note' => [
                    'tags' => [
                        \array_replace($tag, ['label' => 'ISSUE-1']),
                        \array_replace($tag, ['label' => 'ISSUE-2']),
                    ],
                ],
            ]),
        ];

        yield 'third label' => [
            \array_replace_recursive($timeEntry, [
                'note' => [
                    'tags' => [
                        \array_replace($tag, ['label' => 'ISSUE-1']),
                        \array_replace($tag, ['label' => 'some other label']),
                        \array_replace($tag, ['label' => 'ISSUE-2']),
                    ],
                ],
            ]),
        ];
    }
}
