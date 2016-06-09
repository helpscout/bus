<?php
namespace HelpScout\Bus\Tests;

use HelpScout\Bus\Contracts\Command;
use HelpScout\Bus\ClosureHandler;

class ClosureHandlerTest extends \PHPUnit_Framework_TestCase
{
    public function testClosureExecutesOnHandle()
    {
        $commandMock = $this->getMockBuilder(Command::class)->getMock();

        $closureHandler = new ClosureHandler(function($command)
        {
            return $command;
        });

        $this->assertSame($closureHandler->handle($commandMock), $commandMock);
    }
}
