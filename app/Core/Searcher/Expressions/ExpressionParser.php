<?php

namespace App\Core\Searcher\Expressions;

class ExpressionParser {
    /**
     * @param string $expression
     * @return Expression
     * @throws ExpressionException
     */
    public function parse(string $expression): Expression {
        $scanner = new ExpressionScanner($expression);

        // Expression must start with a colon
        $scanner->expect(':');

        // Read function's name
        $function = $scanner->readIdentifier();

        // Read function's arguments, if present
        $arguments = [];

        if ($scanner->has()) {
            $scanner->expect('(');

            while ($scanner->has() && $scanner->peek() !== ')') {
                if (!empty($arguments)) {
                    $scanner->expect(',');
                }

                $arguments[] = $scanner->readValue();
            }

            $scanner->expect(')');
        }

        $scanner->expectEnd();

        return new Expression($function, $arguments);
    }
}
