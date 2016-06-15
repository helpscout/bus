<?php
namespace HelpScout\Bus\Tests;

use HelpScout\Bus\Contracts\Resolver;
use HelpScout\Bus\Exceptions\CouldNotResolveHandlerException;
use HelpScout\Bus\Tests\Assets\DummyHandler;
use HelpScout\Bus\Contracts\Command;
use HelpScout\Bus\Contracts\Handler;
use HelpScout\Bus\Contracts\Translator;
use HelpScout\Bus\ClosureHandler;
use HelpScout\Bus\DefaultResolver;

class DefaultResolverTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Mock of a command class
     *
     * @var mixed
     */
    private $commandMock;

    /**
     * Mock of a translator class
     *
     * @var mixed
     */
    private $translatorMock;

    /**
     * Default Resolver
     *
     * @var Resolver
     */
    private $resolver;

    public function setUp()
    {
        parent::setUp();

        $this->commandMock    = $this->getMockBuilder(Command::class)->getMock();
        $this->translatorMock = $this->getMockBuilder(Translator::class)->getMock();
        $this->translatorMock->method('translate')->willReturn(DummyHandler::class);
        $this->resolver = new DefaultResolver($this->translatorMock);
    }

    public function testReturnAlreadyInstantiatedHandler()
    {
        $handlerMock = $this->getMockBuilder(Handler::class)->getMock();

        self::assertSame(
            $handlerMock,
            $this->resolver->resolve($this->commandMock, $handlerMock)
        );
    }

    public function testReturnHandlerFromString()
    {
        self::assertInstanceOf(
            DummyHandler::class,
            $this->resolver->resolve($this->commandMock, DummyHandler::class)
        );
    }

    public function testReturnClosureHandler()
    {
        $handler = function () {
            // noop
        };

        self::assertInstanceOf(
            ClosureHandler::class,
            $this->resolver->resolve($this->commandMock, $handler)
        );
    }

    public function testReturnTranslatedHandler()
    {
        self::assertInstanceOf(
            DummyHandler::class,
            $this->resolver->resolve($this->commandMock)
        );
    }

    /**
     * @expectedException \HelpScout\Bus\Exceptions\CouldNotResolveHandlerException
     *
     * @return void
     */
    public function testResolverThrowsException()
    {
        $this->translatorMock
            ->method('translate')
            ->willThrowException(new CouldNotResolveHandlerException);

        $this->resolver->resolve($this->commandMock);
    }
}
