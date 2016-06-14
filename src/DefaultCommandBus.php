<?php
namespace HelpScout\Bus;

use Closure;
use HelpScout\Bus\Contracts\Bus;
use HelpScout\Bus\Contracts\Command;
use HelpScout\Bus\Contracts\Handler;
use HelpScout\Bus\Contracts\Resolver;
use SplQueue;

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
    protected $resolver;

    /**
     * @var SplQueue
     */
    protected $queue;

    /**
     * DefaultCommandBus constructor.
     *
     * @param Resolver $resolver
     */
    public function __construct(Resolver $resolver)
    {
        $this->resolver = $resolver;
        $this->queue = new SplQueue;
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

    /**
     * Queue commands to run in sequence
     *
     * @param Command $command
     * @param string|null|Closure|Handler $handler
     * @return $this
     */
    public function queue(Command $command, $handler = null)
    {
        $this->queue->enqueue([$command, $handler]);

        return $this;
    }

    /**
     * Execute all queued commands. When in strict mode,
     * a failed command will stop subsequent executions.
     *
     * @param bool|false $strict
     * @throws \Exception
     */
    public function executeAll($strict = false)
    {
        while (!$this->queue->isEmpty()) {
            list($command, $handler) = $this->queue->dequeue();

            try {
                $this->execute($command, $handler);
            } catch (\Exception $e) {
                if ($strict) {
                    throw $e;
                }
            }
        }
    }


}
