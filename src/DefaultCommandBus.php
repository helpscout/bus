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
 *
 * @package HelpScout\Bus
 */
class DefaultCommandBus implements Bus
{
    /**
     * Resolver for locating and instantiating handlers
     *
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
        $this->queue    = new SplQueue;
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
         *
         * @var Handler $handler
         */
        $handler = $this->resolver->resolve($command, $handler);

        return $handler->handle($command);
    }

    /**
     * Replace the current Resolver with a new instance
     *
     * @param Resolver $resolver
     *
     * @return void
     */
    public function setResolver(Resolver $resolver)
    {
        $this->resolver = $resolver;
    }

    /**
     * Queue commands to run in sequence
     *
     * @param Command                     $command
     * @param string|null|Closure|Handler $handler
     *
     * @return $this
     */
    public function queue(Command $command, $handler = null)
    {
        $this->queue->enqueue([$command, $handler]);

        return $this;
    }

    /**
     * Execute all queued commands. Failing commands will be
     * caught and silenced while subsequent commands will
     * continue to run.
     *
     * @return void
     */
    public function executeAll()
    {
        try {
            $this->executeAllStrict();
        } catch (\Exception $e) {
            $this->executeAll();
        }
    }

    /**
     * Execute all queued commands in strict mode. With strict
     * mode, if one commands fail, no other commands will run
     * and an exception will be thrown.
     *
     * @return void
     */
    public function executeAllStrict()
    {
        while (!$this->queue->isEmpty()) {
            list($command, $handler) = $this->queue->dequeue();

            $this->execute($command, $handler);
        }
    }


}
