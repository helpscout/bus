<?php
namespace HelpScout\Bus\Contracts;

use Closure;

/**
 * Interface Bus
 *
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
     * @param Command                     $command
     * @param string|null|Closure|Handler $handler
     *
     * @return $this
     */
    public function queue(Command $command, $handler = null);

    /**
     * Execute all queued commands. Failing commands will be
     * caught and silenced while subsequent commands will
     * continue to run.
     *
     * @return void
     */
    public function executeAll();

    /**
     * Execute all queued commands in strict mode. With strict
     * mode, if one commands fail, no other commands will run
     * and an exception will be thrown.
     *
     * @return void
     */
    public function executeAllStrict();
}
