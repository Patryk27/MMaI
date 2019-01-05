<?php

namespace Tests\Unit\Core\Searcher\Expressions;

use App\Core\Searcher\Expressions\Expression;
use App\Core\Searcher\Expressions\ExpressionException;
use App\Core\Searcher\Expressions\ExpressionParser;
use Tests\Unit\TestCase;

class ExpressionParserTest extends TestCase {
    /** @var ExpressionParser */
    private $parser;

    /**
     * @return void
     */
    public function setUp(): void {
        $this->parser = new ExpressionParser();
    }

    /**
     * @dataProvider provideCorrectCases
     *
     * @param string $expression
     * @param Expression $expectedExpression
     * @return void
     * @throws ExpressionException
     */
    public function testCorrectCase(string $expression, Expression $expectedExpression): void {
        $this->assertEquals(
            $expectedExpression,
            $this->parser->parse($expression)
        );
    }

    /**
     * @dataProvider provideInvalidCases
     *
     * @param string $expression
     * @param string $expectedError
     * @return void
     * @throws ExpressionException
     */
    public function testInvalidCase(string $expression, string $expectedError): void {
        $this->expectException(ExpressionException::class);
        $this->expectExceptionMessage($expectedError);

        $this->parser->parse($expression);
    }

    /**
     * @return array
     */
    public function provideCorrectCases(): array {
        return [
            [
                ':foo',
                new Expression('foo', []),
            ],

            [
                ':foo(1)',
                new Expression('foo', [1]),
            ],

            [
                ':foo(1, 2)',
                new Expression('foo', [1, 2]),
            ],
        ];
    }

    /**
     * @return array
     */
    public function provideInvalidCases(): array {
        return [
            [
                'foo()',
                'At [1]: [f] was not expected here - expecting [:].',
            ],

            [
                ':()',
                'At [1]: Expected an identifier.',
            ],

            [
                ':foo(',
                'Unexpected end of expression.',
            ],

            [
                ':foo)',
                'At [5]: [)] was not expected here - expecting [(].',
            ],

            [
                ':foo()bar',
                'At [6]: Expression was expected to be terminated here.',
            ],

            [
                ':foo(())',
                'At [5]: Expected a value.',
            ],
        ];
    }
}
