<?php

namespace App\Core\Searcher\Expressions;

class ExpressionProcessor
{

    /**
     * @var ExpressionParser
     */
    private $parser;

    /**
     * @var ExpressionHandler
     */
    private $handler;

    /**
     * @param ExpressionParser $parser
     * @param ExpressionHandler $handler
     */
    public function __construct(
        ExpressionParser $parser,
        ExpressionHandler $handler
    ) {
        $this->parser = $parser;
        $this->handler = $handler;
    }

    /**
     * @param string $expression
     * @return void
     *
     * @throws ExpressionException
     */
    public function process(string $expression): void
    {
        $expression = $this->parser->parse($expression);

        switch ($expression->getFunction()) {
            case 'between':
                $this->handler->between(
                    $expression->getArgument(0),
                    $expression->getArgument(1)
                );

                break;

            case 'regex':
                $this->handler->regex(
                    $expression->getArgument(0)
                );

                break;

            default:
                throw new ExpressionException(
                    sprintf('Unknown function: [%s].', $expression->getFunction())
                );
        }
    }

}
