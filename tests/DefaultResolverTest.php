<?php
namespace HelpScout\Bus\Tests;

use HelpScout\Bus\Exceptions\CouldNotResolveHandlerException;
use HelpScout\Bus\Tests\Assets\DummyHandler;
use HelpScout\Bus\Contracts\Command;
use HelpScout\Bus\Contracts\Handler;
use HelpScout\Bus\Contracts\Translator;
use HelpScout\Bus\ClosureHandler;
use HelpScout\Bus\DefaultResolver;

class DefaultResolverTest extends \PHPUnit_Framework_TestCase
{
    private $commandMock;
    private $translatorMock;
    private $resolver;

    public function setUp()
    {
        parent::setUp();

        $this->commandMock = $this->getMockBuilder(Command::class)->getMock();
        $this->translatorMock = $this->getMockBuilder(Translator::class)->getMock();
        $this->translatorMock->method('translate')->willReturn(DummyHandler::class);
        $this->resolver = new DefaultResolver($this->translatorMock);
    }

    public function testReturnAlreadyInstantiatedHandler()
    {
        $handlerMock = $this->getMockBuilder(Handler::class)->getMock();

        $this->assertSame($handlerMock, $this->resolver->resolve($this->commandMock, $handlerMock));
    }

    public function testReturnHandlerFromString()
    {
        $this->assertTrue($this->resolver->resolve($this->commandMock, DummyHandler::class) instanceof DummyHandler);
    }

    public function testReturnClosureHandler()
    {
        $handler = function(){};

        $this->assertTrue($this->resolver->resolve($this->commandMock, $handler) instanceof ClosureHandler);
    }

    public function testReturnTranslatedHandler()
    {
        $this->assertTrue($this->resolver->resolve($this->commandMock) instanceof DummyHandler);
    }

    /**
     * @expectedException \HelpScout\Bus\Exceptions\CouldNotResolveHandlerException
     */
    public function testResolverThrowsException()
    {
        $this->translatorMock->method('translate')->willThrowException(new CouldNotResolveHandlerException);

        $this->resolver->resolve($this->commandMock);
    }
}
