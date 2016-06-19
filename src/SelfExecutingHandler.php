<?php
namespace HelpScout\Bus;

use HelpScout\Bus\Contracts\Command;
use HelpScout\Bus\Contracts\Handler;
use HelpScout\Bus\Contracts\SelfHandler;

/**
 * Class SelfExecutingHandler
 *
 * @package HelpScout\Bus
 */
class SelfExecutingHandler implements Command, Handler
{
    /**
     * Self handling command
     *
     * @var callable
     */
    private $handler;

    /**
     * SelfExecutingHandler constructor.
     *
     * @param SelfHandler $handler
     */
    public function __construct(SelfHandler $handler)
    {
        $this->handler = $handler;
    }

    /**
     * Execute a self handling command
     *
     * @param Command $command
     *
     * @return mixed
     */
    public function handle(Command $command)
    {
        return $this->handler->handle();
    }
}
