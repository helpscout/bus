<?php
namespace HelpScout\Bus\Tests;

use HelpScout\Bus\Tests\Assets\SelfHandlingCommand;
use HelpScout\Bus\SelfExecutingHandler;

class SelfHandlerTest extends \PHPUnit_Framework_TestCase
{
    public function testSelfExecutingHandlerExecutesCommandHandleMethod()
    {
        $command     = new SelfHandlingCommand;
        $selfHandler = new SelfExecutingHandler($command);

        self::assertSame(
            $selfHandler->handle($command),
            $command->handle()
        );
    }
}
