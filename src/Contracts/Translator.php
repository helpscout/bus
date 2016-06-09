<?php
namespace HelpScout\Bus\Contracts;

/**
 * Interface Translator
 * @package HelpScout\Bus\Contracts
 */
interface Translator
{
    /**
     * Find a Handler for a Command
     *
     * @param Command $command
     *
     * @return string
     */
    public function translate(Command $command);
}
