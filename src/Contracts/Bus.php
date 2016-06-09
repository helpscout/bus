<?php
namespace HelpScout\Bus\Contracts;

/**
 * Interface Bus
 * @package HelpScout\Bus\Contracts
 */
interface Bus
{
    /**
     * Run the handler for a command, locating a handler if needed
     *
     * @param Command $command
     * @param null    $handler
     *
     * @return mixed
     */
    public function execute(Command $command, $handler = null);
}
