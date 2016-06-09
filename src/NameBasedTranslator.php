<?php
namespace HelpScout\Bus;

use HelpScout\Bus\Contracts\Command;
use HelpScout\Bus\Contracts\Translator;
use HelpScout\Bus\Exceptions\HandlerNotRegisteredException;

class NameBasedTranslator implements Translator
{
    /**
     * Find a Handler for a Command
     *
     * @param Command $command
     *
     * @return string
     *
     * @throws HandlerNotRegisteredException
     */
    public function translate(Command $command)
    {
        $commandClass = get_class($command);
        $handler      = substr_replace(
            $commandClass,
            'Handler',
            strrpos($commandClass, 'Command')
        );

        if (!class_exists($handler)) {
            $message = "Command handler [$handler] does not exist.";
            throw new HandlerNotRegisteredException($message);
        }

        return $handler;
    }
}
