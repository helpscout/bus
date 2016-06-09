<?php
namespace HelpScout\Bus\Tests;

use HelpScout\Bus\Contracts\Command;
use HelpScout\Bus\Contracts\Handler;
use HelpScout\Bus\Contracts\Resolver;
use HelpScout\Bus\DefaultCommandBus;

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
}
