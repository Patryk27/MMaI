<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Joselfonseca\LaravelTactician\CommandBusInterface;

class CommandBusServiceProvider extends ServiceProvider
{

    private const COMMANDS = [
        // Pages
        \App\CommandBus\Commands\Pages\CreateCommand::class => \App\CommandBus\Handlers\Pages\CreateHandler::class,
        \App\CommandBus\Commands\Pages\UpdateCommand::class => \App\CommandBus\Handlers\Pages\UpdateHandler::class,

        // Routes
        \App\CommandBus\Commands\Routes\DeleteCommand::class => \App\CommandBus\Handlers\Routes\DeleteHandler::class,
        \App\CommandBus\Commands\Routes\RerouteCommand::class => \App\CommandBus\Handlers\Routes\RerouteHandler::class,
    ];

    /**
     * @param CommandBusInterface $commandBus
     * @return void
     */
    public function boot(
        CommandBusInterface $commandBus
    ): void {
        foreach (self::COMMANDS as $command => $handler) {
            $commandBus->addHandler($command, $handler);
        }
    }

}
