<?php

namespace Tests\Unit\Core\Searcher\Expressions;

use App\Core\Searcher\Expressions\ExpressionException;
use App\Core\Searcher\Expressions\ExpressionScanner;
use LogicException;
use PHPUnit\Framework\AssertionFailedError;
use Tests\Unit\TestCase;

class ExpressionScannerTest extends TestCase {
    /**
     * @dataProvider provideCorrectCases
     *
     * @param string $expression
     * @param array $expectedTokens
     * @return void
     * @throws ExpressionException
     */
    public function testCorrectCase(string $expression, array $expectedTokens): void {
        $scanner = new ExpressionScanner($expression);

        while (!empty($expectedTokens)) {
            $expectedToken = array_shift($expectedTokens);

            switch ($expectedToken[0]) {
                case 'identifier':
                    $actualToken = $scanner->readIdentifier();
                    break;

                case 'number':
                    $actualToken = $scanner->readNumber();
                    break;

                case 'string':
                    $actualToken = $scanner->readString();
                    break;

                case 'value':
                    $actualToken = $scanner->readValue();
                    break;

                default:
                    throw new LogicException(
                        sprintf('Unknown token type: [%s]', $expectedToken[0])
                    );
            }

            $this->assertEquals($expectedToken[1], $actualToken);
        }

        if ($scanner->has()) {
            throw new AssertionFailedError('Scanner should be empty at the end of the test.');
        }
    }

    /**
     * @return array
     */
    public function provideCorrectCases(): array {
        return [
            // Validate scanning identifiers
            [
                'foo bar',
                [
                    ['identifier', 'foo'],
                    ['identifier', 'bar'],
                ],
            ],

            // Validate scanning strings
            [
                '\'foo bar\' "foo bar" /foo bar/ /foo \/ bar/',
                [
                    ['string', 'foo bar'],
                    ['string', 'foo bar'],
                    ['string', 'foo bar'],
                    ['string', 'foo / bar'],
                ],
            ],

            // Validate scanning numbers
            [
                '1234 12.34',
                [
                    ['number', '1234'],
                    ['number', '12.34'],
                ],
            ],

            // Validate scanning values
            [
                '1234 /foo bar/ foo bar',
                [
                    ['value', '1234'],
                    ['value', 'foo bar'],
                    ['value', 'foo'],
                    ['value', 'bar'],
                ],
            ],
        ];
    }
}
