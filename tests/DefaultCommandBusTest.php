<?php
namespace HelpScout\Bus\Tests;

use Exception;
use HelpScout\Bus\Contracts\Command;
use HelpScout\Bus\Contracts\Handler;
use HelpScout\Bus\Contracts\Resolver;
use HelpScout\Bus\DefaultCommandBus;
use HelpScout\Bus\Tests\Assets\DummyCommand;
use HelpScout\Bus\Tests\Assets\DummyHandler;
use HelpScout\Bus\Tests\Assets\FooCommand;
use HelpScout\Bus\Tests\Assets\SelfHandlingCommand;

class DefaultCommandBusTest extends \PHPUnit_Framework_TestCase
{
    public function testBusExecutesCommandHandler()
    {
        $testMessage = 'The wheels on the bus go round and round';

        $handlerMock = $this->getMockBuilder(Handler::class)->getMock();
        $handlerMock->method('handle')->willReturn($testMessage);

        $resolverMock = $this->getMockBuilder(Resolver::class)->getMock();
        $resolverMock->method('resolve')->willReturn($handlerMock);

        $commandMock = $this->getMockBuilder(Command::class)->getMock();

        $bus = new DefaultCommandBus($resolverMock);

        self::assertEquals($bus->execute($commandMock), $testMessage);
    }

    public function testBusExecutesSelfHandlingCommandHandler()
    {
        $testMessage = 'The wheels on the bus go round and round';

        $selfHandlerMock = $this->getMockBuilder(SelfHandlingCommand::class)->getMock();
        $selfHandlerMock->method('handle')->willReturn($testMessage);

        $resolverMock = $this->getMockBuilder(Resolver::class)->getMock();
        $resolverMock->method('resolve')->willReturn($selfHandlerMock);

        $bus = new DefaultCommandBus($resolverMock);

        self::assertEquals($bus->execute($selfHandlerMock), $testMessage);
    }

    public function testSetResolverOnBus()
    {
        $resolverMock = $this->getMockBuilder(Resolver::class)->getMock();
        $bus          = new DefaultCommandBus($resolverMock);

        $reflectionClass    = new \ReflectionClass(DefaultCommandBus::class);
        $reflectionProperty = $reflectionClass->getProperty('resolver');
        $reflectionProperty->setAccessible(true);

        self::assertSame($resolverMock, $reflectionProperty->getValue($bus));

        $newResolverMock = $this->getMockBuilder(Resolver::class)->getMock();
        $bus->setResolver($newResolverMock);

        self::assertSame($newResolverMock, $reflectionProperty->getValue($bus));
    }

    public function testQueueMethodEnqueuesCommand()
    {
        $resolverMock = $this->getMockBuilder(Resolver::class)->getMock();
        $bus          = new DefaultCommandBus($resolverMock);

        $reflectionClass    = new \ReflectionClass(DefaultCommandBus::class);
        $reflectionProperty = $reflectionClass->getProperty('queue');
        $reflectionProperty->setAccessible(true);

        $queue = $reflectionProperty->getValue($bus);

        self::assertInstanceOf(\SplQueue::class, $queue);
        self::assertEquals(0, $queue->count());

        $commandMock = $this->getMockBuilder(Command::class)->getMock();

        $bus->queue($commandMock);

        self::assertEquals(1, $queue->count());
    }

    public function testExecuteAllExecutesQueuedCommands()
    {
        $handlerMock = $this->getMockBuilder(Handler::class)->getMock();
        $handlerMock->expects($this->once())->method('handle');

        $resolverMock = $this->getMockBuilder(Resolver::class)->getMock();
        $resolverMock->method('resolve')->willReturn($handlerMock);
        $bus = new DefaultCommandBus($resolverMock);

        $commandMock = $this->getMockBuilder(Command::class)->getMock();

        $bus->queue($commandMock, $handlerMock);

        $bus->executeAll();
    }

    public function testExecuteAllRecoversFromAThrownExeception()
    {
        $commandMock = $this->getMockBuilder(Command::class)->getMock();

        $handlerMock = $this->getMockBuilder(Handler::class)->getMock();
        $handlerMock->expects($this->once())->method('handle');

        $errorHandlerMock = $this->getMockBuilder(Handler::class)->getMock();
        $errorHandlerMock->expects($this->once())
            ->method('handle')
            ->willThrowException(new Exception);

        $recoveredHandlerMock = $this->getMockBuilder(Handler::class)->getMock();
        $recoveredHandlerMock->expects($this->once())->method('handle');

        $resolverMock = $this->getMockBuilder(Resolver::class)->getMock();

        $resolverMock->expects($this->any())
            ->method('resolve')
            ->will($this->onConsecutiveCalls($handlerMock, $errorHandlerMock, $recoveredHandlerMock));

        $bus = new DefaultCommandBus($resolverMock);

        $bus->queue($commandMock, $handlerMock)
            ->queue($commandMock, $errorHandlerMock)
            ->queue($commandMock, $recoveredHandlerMock)
            ->executeAll();
    }

    /**
     * @expectedException \Exception
     *
     * @return void
     */
    public function testExecuteAllStrictStopsExecution()
    {
        $commandMock = $this->getMockBuilder(Command::class)->getMock();

        $handlerMock = $this->getMockBuilder(Handler::class)->getMock();
        $handlerMock->expects($this->once())->method('handle');

        $errorHandlerMock = $this->getMockBuilder(Handler::class)->getMock();
        $errorHandlerMock->expects($this->once())
            ->method('handle')
            ->willThrowException(new Exception);

        $failedHandlerMock = $this->getMockBuilder(Handler::class)->getMock();
        $failedHandlerMock->expects($this->never())->method('handle');

        $resolverMock = $this->getMockBuilder(Resolver::class)->getMock();

        $resolverMock->expects($this->any())
            ->method('resolve')
            ->will($this->onConsecutiveCalls($handlerMock, $errorHandlerMock));

        $bus = new DefaultCommandBus($resolverMock);

        $bus->queue($commandMock, $handlerMock)
            ->queue($commandMock, $errorHandlerMock)
            ->queue($commandMock, $failedHandlerMock)
            ->executeAllStrict();
    }
}
