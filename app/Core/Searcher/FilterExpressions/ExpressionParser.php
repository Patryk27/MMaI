<?php

namespace App\Core\Searcher\FilterExpressions;

class ExpressionParser
{

    /**
     * @param string $expression
     * @return array
     *
     * @throws ExpressionException
     */
    public function parse(string $expression): array
    {
        $expression = $this->preparse($expression);

        if (!preg_match('/^([a-z]+)\((.+)\)$/', $expression, $matches)) {
            throw new ExpressionException('Expression is malformed.');
        }

        return [
            'name' => $matches[1],
            'arguments' => explode(',', $matches[2]),
        ];
    }

    /**
     * @param string $expression
     * @return string
     *
     * @throws ExpressionException
     */
    private function preparse(string $expression): string
    {
        $expression = trim($expression);

        if (strlen($expression) === 0) {
            throw new ExpressionException('Cannot parse an empty expression.');
        }

        if ($expression[0] !== ':') {
            throw new ExpressionException('Expression must start with a colon (:).');
        }

        return mb_substr($expression, 1, mb_strlen($expression));
    }

}
