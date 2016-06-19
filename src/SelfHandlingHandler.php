<?php
namespace HelpScout\Bus;

use HelpScout\Bus\Contracts\Command;
use HelpScout\Bus\Contracts\Handler;
use HelpScout\Bus\Contracts\SelfHandling;

/**
 * Class SelfExecutingHandler
 *
 * @package HelpScout\Bus
 */
class SelfHandlingHandler implements Command, Handler
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
     * @param SelfHandling $handler
     */
    public function __construct(SelfHandling $handler)
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
