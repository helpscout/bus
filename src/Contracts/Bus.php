<?php
namespace HelpScout\Bus\Contracts;

use Closure;

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

    /**
     * Queue commands to run in sequence
     *
     * @param Command $command
     * @param string|null|Closure|Handler $handler
     */
    public function queue(Command $command, $handler = null);

    /**
     * Execute all queued commands. When in strict mode,
     * a failed command will stop subsequent executions.
     *
     * @param bool|false $strict
     * @return void
     */
    public function executeAll($strict = false);
}
