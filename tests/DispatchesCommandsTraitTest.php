<?php
namespace HelpScout\Bus\Tests;

use HelpScout\Bus\Contracts\Command;
use HelpScout\Bus\Contracts\Handler;
use HelpScout\Bus\DispatchesCommandsTrait;

class DispatchesCommandsTraitTest extends \PHPUnit_Framework_TestCase
{
    use DispatchesCommandsTrait;

    public function testDispatchExecutesCommand()
    {
        $response = 'foo';

        $commandMock = $this->getMockBuilder(Command::class)->getMock();
        $handlerMock = $this->getMockBuilder(Handler::class)
            ->setMethods(['handle'])
            ->getMock();

        $handlerMock->expects($this->once())
            ->method('handle')
            ->with($commandMock)
            ->willReturn($response);

        $this->assertEquals(
            $response,
            $this->dispatch($commandMock, $handlerMock)
        );
    }
}
