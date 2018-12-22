<?php

namespace Tests\Assertions\Routes;

use App\Routes\RoutesFacade;
use PHPUnit\Framework\Constraint\Constraint as PHPUnitConstraint;

abstract class Constraint extends PHPUnitConstraint
{
    /** @var RoutesFacade */
    protected $routesFacade;

    public function __construct(RoutesFacade $routesFacade)
    {
        parent::__construct();

        $this->routesFacade = $routesFacade;
    }

    /**
     * @inheritDoc
     */
    public function toString(): string
    {
        return '';
    }
}
