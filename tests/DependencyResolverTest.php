<?php
namespace HelpScout\Bus\Tests;

use HelpScout\Bus\Contracts\Command;
use HelpScout\Bus\Contracts\Translator;
use HelpScout\Bus\DependencyResolver;
use HelpScout\Bus\Tests\Assets\Bar;
use HelpScout\Bus\Tests\Assets\Baz;
use HelpScout\Bus\Tests\Assets\Foo;

class DependencyResolverTest extends \PHPUnit_Framework_TestCase
{
    public function testResolverInstantiatesDependencies()
    {
        $translatorMock     = $this->getMockBuilder(Translator::class)->getMock();
        $commandMock        = $this->getMockBuilder(Command::class)->getMock();
        $dependencyResolver = new DependencyResolver($translatorMock);

        /**
         * @var Foo $foo
         */
        $foo = $dependencyResolver->resolve($commandMock, Foo::class);

        self::assertInstanceOf(Foo::class, $foo);
        self::assertInstanceOf(Bar::class, $foo->bar);
        self::assertInstanceOf(Baz::class, $foo->bar->baz);
    }
}
