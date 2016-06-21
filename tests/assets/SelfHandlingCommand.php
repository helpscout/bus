<?php
namespace HelpScout\Bus\Tests\Assets;

use HelpScout\Bus\Contracts\Command;
use HelpScout\Bus\Contracts\SelfHandling;

class SelfHandlingCommand implements Command, SelfHandling
{
    public function handle(Command $command)
    {
        // NOOP
    }
}
