<?php
namespace HelpScout\Bus;

use HelpScout\Bus\Contracts\Bus;
use HelpScout\Bus\Contracts\Command;
use HelpScout\Bus\Contracts\Handler;
use HelpScout\Bus\Contracts\Resolver;

/**
 * Class DefaultCommandBus
 * @package HelpScout\Bus
 */
class DefaultCommandBus implements Bus
{
    /**
     * Resolver for locating and instantiating handlers
     * @var Resolver
     */
    private $resolver;

    /**
     * DefaultCommandBus constructor.
     *
     * @param Resolver $resolver
     */
    public function __construct(Resolver $resolver)
    {
        $this->resolver = $resolver;
    }

    /**
     * Run the handler on a handler, locating a handler if needed
     *
     * @param Command $command
     * @param null    $handler
     *
     * @return mixed
     */
    public function execute(Command $command, $handler = null)
    {
        /**
         * The correct handler for the given command
         * @var Handler $handler
         */
        $handler = $this->resolver->resolve($command, $handler);

        return $handler->handle($command);
    }

    /**
     * Replace the current Resolver with a new instance
     *
     * @param Resolver $resolver
     */
    public function setResolver(Resolver $resolver)
    {
        $this->resolver = $resolver;
    }
}

/* End of file DefaultCommandBus.php */
