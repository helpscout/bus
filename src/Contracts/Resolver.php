<?php
namespace HelpScout\Bus\Contracts;

/**
 * Interface Resolver
 * @package HelpScout\Bus\Contracts
 */
interface Resolver
{
    /**
     * Locate a handler for a command
     *
     * @param Command $command
     * @param null    $handler
     *
     * @return mixed
     */
    public function resolve(Command $command, $handler = null);
}
