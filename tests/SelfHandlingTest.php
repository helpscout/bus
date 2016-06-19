<?php
namespace HelpScout\Bus\Tests;

use HelpScout\Bus\Tests\Assets\SelfHandlingCommand;
use HelpScout\Bus\SelfHandlingHandler;

class SelfHandlingTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Default Resolver
     *
     * @var SelfHandlingCommand
     */
    private $command;

    public function testSelfExecutingHandlerExecutesCommandHandleMethod()
    {
        $command     = new SelfHandlingCommand;
        $selfHandler = new SelfHandlingHandler($command);

        self::assertSame(
            $selfHandler->handle($command),
            $command->handle()
        );
    }
}
