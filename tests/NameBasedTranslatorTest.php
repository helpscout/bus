<?php
namespace HelpScout\Bus\Tests;

use HelpScout\Bus\Exceptions\HandlerNotRegisteredException;
use HelpScout\Bus\Tests\Assets\DummyCommand;
use HelpScout\Bus\Tests\Assets\DummyHandler;
use HelpScout\Bus\Tests\Assets\FooCommand;
use HelpScout\Bus\NameBasedTranslator;

class NameBasedTranslatorTest extends \PHPUnit_Framework_TestCase
{
    public function testTranslatorFindsAndReturnsCommandHandler()
    {
        $translator = new NameBasedTranslator;

        self::assertEquals(
            $translator->translate(new DummyCommand),
            DummyHandler::class
        );
    }

    /**
     * @expectedException HandlerNotRegisteredException
     *
     * @return void
     */
    public function testTranslatorThrowsExceptionWhenHandlerDoesNotExist()
    {
        $translator = new NameBasedTranslator;

        $translator->translate(new FooCommand);
    }
}
