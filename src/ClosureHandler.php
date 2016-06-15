<?php
namespace HelpScout\Bus;

use HelpScout\Bus\Contracts\Command;
use HelpScout\Bus\Contracts\Handler;

/**
 * Class ClosureHandler
 *
 * @package HelpScout\Bus
 */
class ClosureHandler implements Handler
{
    /**
     * Closure
     *
     * @var callable
     */
    private $closure;

    /**
     * ClosureHandler constructor.
     *
     * @param \Closure $closure
     */
    public function __construct(\Closure $closure)
    {
        $this->closure = $closure;
    }

    /**
     * Execute a closure handler
     *
     * @param Command $command
     *
     * @return mixed
     */
    public function handle(Command $command)
    {
        $closure = $this->closure;

        return $closure($command);
    }
}
