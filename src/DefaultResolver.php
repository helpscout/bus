<?php
namespace HelpScout\Bus;

use Closure;
use HelpScout\Bus\Contracts\Command;
use HelpScout\Bus\Contracts\Handler;
use HelpScout\Bus\Contracts\Resolver;
use HelpScout\Bus\Contracts\Translator;
use HelpScout\Bus\Contracts\SelfHandling;
use HelpScout\Bus\Exceptions\CouldNotResolveHandlerException;

/**
 * Class DefaultResolver
 *
 * @package HelpScout\Bus
 */
class DefaultResolver implements Resolver
{
    /**
     * To locate an appropriate handler for a command
     *
     * @var Translator
     */
    private $translator;

    /**
     * DefaultResolver constructor.
     *
     * @param Translator $translator
     */
    public function __construct(Translator $translator)
    {
        $this->translator = $translator;
    }

    /**
     * Find an appropriate handler for a command
     *
     * @param Command $command
     * @param mixed   $handler
     *
     * @return null|ClosureHandler
     * @throws CouldNotResolveHandlerException
     */
    public function resolve(Command $command, $handler = null)
    {
        try {
            switch (true) {
                case $handler instanceof Handler:
                    return $handler;

                case is_string($handler):
                    return $this->initClass($handler);

                case $handler instanceof Closure:
                    return new ClosureHandler($handler);

                case $command instanceof SelfHandling:
                    return new SelfHandlingHandler($command);

                default:
                    $translatedClass = $this->translator->translate($command);
                    return $this->initClass($translatedClass);
            }
        } catch (\Exception $e) {
            // If we made it here, do nothing and let's just throw an exception.
        }

        throw new CouldNotResolveHandlerException('Could not locate a handler');
    }

    /**
     * Create a handler with dependencies from HelpscoutFactory
     *
     * @param string $className
     *
     * @return Handler
     */
    protected function initClass($className)
    {
        return new $className();
    }
}
