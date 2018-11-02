<?php

namespace App\Core\Searcher\Expressions;

final class Expression
{

    /**
     * Function's name, e.g.: "between".
     *
     * @var string
     */
    private $function;

    /**
     * Function's arguments, e.g.: [100, 200].
     *
     * @var array
     */
    private $arguments;

    /**
     * @param string $function
     * @param array $arguments
     */
    public function __construct(
        string $function,
        array $arguments
    ) {
        $this->function = $function;
        $this->arguments = $arguments;
    }

    /**
     * @return string
     */
    public function getFunction(): string
    {
        return $this->function;
    }

    /**
     * @return array
     */
    public function getArguments(): array
    {
        return $this->arguments;
    }

    /**
     * @param int $idx
     * @return mixed
     *
     * @throws ExpressionException
     */
    public function getArgument(int $idx)
    {
        if (!array_key_exists($idx, $this->arguments)) {
            throw new ExpressionException(
                sprintf('Argument #%d was not found.', $idx)
            );
        }

        return $this->arguments[$idx];
    }

}
