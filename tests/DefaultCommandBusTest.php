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

        $this->assertEquals($bus->execute($commandMock), $testMessage);
    }

    public function testSetResolverOnBus()
    {
        $resolverMock = $this->getMockBuilder(Resolver::class)->getMock();
        $bus = new DefaultCommandBus($resolverMock);

        $reflectionClass = new \ReflectionClass(DefaultCommandBus::class);
        $reflectionProperty = $reflectionClass->getProperty('resolver');
        $reflectionProperty->setAccessible(true);

        $this->assertSame($resolverMock, $reflectionProperty->getValue($bus));

        $newResolverMock = $this->getMockBuilder(Resolver::class)->getMock();
        $bus->setResolver($newResolverMock);

        $this->assertSame($newResolverMock, $reflectionProperty->getValue($bus));
    }

    public function testQueueMethodEnqueuesCommand()
    {
        $resolverMock = $this->getMockBuilder(Resolver::class)->getMock();
        $bus = new DefaultCommandBus($resolverMock);

        $reflectionClass = new \ReflectionClass(DefaultCommandBus::class);
        $reflectionProperty = $reflectionClass->getProperty('queue');
        $reflectionProperty->setAccessible(true);

        $queue = $reflectionProperty->getValue($bus);

        $this->assertInstanceOf(\SplQueue::class, $queue);
        $this->assertEquals(0, $queue->count());

        $commandMock = $this->getMockBuilder(Command::class)->getMock();

        $bus->queue($commandMock);

        $this->assertEquals(1, $queue->count());
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

    /**
     * @expectedException \Exception
     */
    public function testExecuteAllStopsExecutionWhenSetToStrict()
    {
        $commandMock = $this->getMockBuilder(Command::class)->getMock();

        $handlerMock = $this->getMockBuilder(Handler::class)->getMock();
        $handlerMock->expects($this->once())->method('handle');

        $errorHandlerMock = $this->getMockBuilder(Handler::class)->getMock();
        $errorHandlerMock->expects($this->once())->method('handle')->willThrowException(new Exception);

        $failedHandlerMock = $this->getMockBuilder(Handler::class)->getMock();
        $failedHandlerMock->expects($this->never())->method('handle');

        $resolverMock = $this->getMockBuilder(Resolver::class)->getMock();

        $resolverMock->expects($this->any())
            ->method('resolve')
            ->will($this->onConsecutiveCalls($handlerMock, $errorHandlerMock));

        $bus = new DefaultCommandBus($resolverMock);

        $bus->queue($commandMock, $handlerMock);
        $bus->queue($commandMock, $errorHandlerMock);
        $bus->queue($commandMock, $failedHandlerMock);

        $bus->executeAll(true);
    }
}
