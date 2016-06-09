<?php
namespace HelpScout\Bus;

use HelpScout\Bus\Contracts\Command;
use HelpScout\Bus\Contracts\Handler;

trait DispatchesCommandsTrait
{
    /**
     * Execute a command DTO with a handler
     *
     * If a handler is not provided, the bus will attempt to locate an
     * appropriate handler for
     *
     * @param Command $command
     * @param Handler $handler
     *
     * @return mixed
     */
    public function dispatch(Command $command, $handler = null)
    {
        $bus = new DefaultCommandBus(new DefaultResolver(new NameBasedTranslator));
        return $bus->execute($command, $handler);
    }
}
