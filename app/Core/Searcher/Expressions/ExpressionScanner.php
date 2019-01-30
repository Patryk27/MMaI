<?php

namespace App\Core\Searcher\Expressions;

class ExpressionScanner {

    /** @var string */
    private $expression;

    /** @var int */
    private $position;

    public function __construct(string $expression) {
        $this->expression = $expression;
        $this->position = 0;
    }

    /**
     * Returns whether there are still any characters to read.
     *
     * @return bool
     */
    public function has(): bool {
        return $this->position < mb_strlen($this->expression);
    }

    /**
     * Reads a single character.
     * Throws an exception if the stream is empty.
     *
     * @return string
     * @throws ExpressionException
     */
    public function read(): string {
        if (!$this->has()) {
            throw new ExpressionException('Unexpected end of expression.');
        }

        return $this->expression[$this->position++];
    }

    /**
     * Returns the next character from the stream without consuming it.
     *
     * @return string|null
     */
    public function peek(): ?string {
        return $this->expression[$this->position] ?? null;
    }

    /**
     * Reads the next character and throws an exception if it's not the given
     * one.
     *
     * @param string $expected
     * @return void
     * @throws ExpressionException
     */
    public function expect(string $expected): void {
        $this->skipWhitespaces();

        $actual = $this->read();

        if ($actual !== $expected) {
            throw new ExpressionException(sprintf(
                'At [%d]: [%s] was not expected here - expecting [%s].', $this->position, $actual, $expected
            ));
        }
    }

    /**
     * Throws an exception if stream is not finished.
     *
     * @return void
     * @throws ExpressionException
     */
    public function expectEnd(): void {
        if ($this->has()) {
            throw new ExpressionException(sprintf(
                'At [%d]: Expression was expected to be terminated here.', $this->position
            ));
        }
    }

    /**
     * Reads a single identifier (a non-empty sequence of letters & digits).
     * Throws an exception if stream contains no identifier at this point.
     *
     * @return string
     * @throws ExpressionException
     */
    public function readIdentifier(): string {
        $this->skipWhitespaces();

        $identifier = $this->readWhile('ctype_alnum');

        if (strlen($identifier) === 0) {
            throw new ExpressionException(sprintf(
                'At [%d]: Expected an identifier.', $this->position
            ));
        }

        return $identifier;
    }

    /**
     * Reads a single string (a sequence of characters enclosed between ' or ").
     * Throws an exception if stream contains no string at this point.
     *
     * @return string
     * @throws ExpressionException
     */
    public function readString(): string {
        $this->skipWhitespaces();

        $string = '';

        // Read the enclosure
        $enclosure = $this->read();

        if (!in_array($enclosure, ['"', "'", '/'])) {
            throw new ExpressionException(sprintf(
                'At [%d]: Expected a string.', $this->position
            ));
        }

        // Read the string
        while ($char = $this->read()) {
            if ($char === $enclosure) {
                break;
            }

            if ($char === '\\') {
                $char = $this->read();
            }

            $string .= $char;
        }

        return $string;
    }

    /**
     * Reads a single number (a sequence of digits with an optional dot inside).
     * Throws an exception if stream contains no number at this point.
     *
     * @return string
     * @throws ExpressionException
     */
    public function readNumber(): string {
        $this->skipWhitespaces();

        $number = $this->readWhile(function (?string $ch): bool {
            return ctype_digit($ch) || $ch === '.';
        });

        if (strlen($number) === 0) {
            throw new ExpressionException(sprintf(
                'At [%d]: Expected a number.', $this->position
            ));
        }

        return $number;
    }

    /**
     * Reads a single value (a string, a number or an identifier).
     * Throws an exception if stream contains no value at this point.
     *
     * @return string
     * @throws ExpressionException
     */
    public function readValue(): string {
        $this->skipWhitespaces();

        $char = $this->peek();

        switch (true) {
            case in_array($char, ['"', "'", '/']):
                return $this->readString();

            case ctype_digit($char):
                return $this->readNumber();

            case ctype_alpha($char):
                return $this->readIdentifier();

            default:
                throw new ExpressionException(sprintf(
                    'At [%d]: Expected a value.', $this->position
                ));
        }
    }

    /**
     * @return void
     * @throws ExpressionException
     */
    private function skipWhitespaces(): void {
        $this->readWhile(function (?string $char): bool {
            return $char === ' ';
        });
    }

    /**
     * Reads all characters until the predicate returns `false`.
     *
     * @param callable $predicate
     * @return string
     * @throws ExpressionException
     */
    private function readWhile(callable $predicate): string {
        $result = '';

        while ($predicate($this->peek())) {
            $result .= $this->read();
        }

        return $result;
    }

}
