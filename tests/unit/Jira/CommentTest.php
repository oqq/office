<?php

declare(strict_types=1);

namespace Oqq\Office\Test\Jira;

use Oqq\Office\Jira\Comment;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Oqq\Office\Jira\Comment
 */
final class CommentTest extends TestCase
{
    /**
     * @dataProvider validValueProvider
     */
    public function testItWillCreateFromPerfectValue(string $valueExample): void
    {
        $valueObject = Comment::fromString($valueExample);

        Assert::assertSame($valueExample, $valueObject->toString());
    }

    /**
     * @return iterable<array-key, array{0: string}>
     */
    public function validValueProvider(): iterable
    {
        yield [''];
        yield ['Review'];
    }
}
