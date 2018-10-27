<?php

namespace App\Core\Searcher\FilterExpressions;

class ExpressionProcessor
{

    /**
     * @var ExpressionParser
     */
    private $parser;

    /**
     * @var OpcodesHandler
     */
    private $handler;

    /**
     * @param ExpressionParser $parser
     * @param OpcodesHandler $handler
     */
    public function __construct(
        ExpressionParser $parser,
        OpcodesHandler $handler
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
        // Parse the command
        $opcode = $this->parser->parse($expression);

        // Extract command parts
        $opcodeName = $opcode['name'];
        $opcodeArguments = $opcode['arguments'];

        // Dispatch depending on the command name
        switch ($opcodeName) {
            case 'between':
                $this->handler->between($opcodeArguments[0], $opcodeArguments[1]);
                break;

            case 'regex':
                $this->handler->regex($opcodeArguments[0]);
                break;

            default:
                throw new ExpressionException(
                    sprintf('Unknown command: [%s].', $opcodeName)
                );
        }
    }

}
