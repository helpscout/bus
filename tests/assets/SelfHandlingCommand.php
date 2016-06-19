<?php
namespace HelpScout\Bus\Tests\Assets;

use HelpScout\Bus\Contracts\Command;
use HelpScout\Bus\Contracts\SelfHandler;

class SelfHandlingCommand implements Command, SelfHandler
{
    public function handle()
    {
        // NOOP
    }
}
