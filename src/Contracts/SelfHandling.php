<?php
namespace HelpScout\Bus\Contracts;

/**
 * Interface Handler
 *
 * @package HelpScout\Bus\Contracts
 */
interface SelfHandling
{
    /**
     * Run the actions on a command DTO
     *
     * @return mixed
     */
    public function handle();
}
