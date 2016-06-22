<?php
namespace HelpScout\Bus\Contracts;

/**
 * Interface Handler
 *
 * @package HelpScout\Bus\Contracts
 */
interface SelfHandling extends Handler
{
    /**
     * Run the actions on a command DTO
     *
     * @param Command $command
     *
     * @return mixed
     */
    public function handle(Command $command);
}
