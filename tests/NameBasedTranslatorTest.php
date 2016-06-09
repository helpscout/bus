<?php
namespace HelpScout\Bus\Tests;

use HelpScout\Bus\Tests\Assets\DummyCommand;
use HelpScout\Bus\Tests\Assets\DummyHandler;
use HelpScout\Bus\Tests\Assets\FooCommand;
use HelpScout\Bus\NameBasedTranslator;

class NameBasedTranslatorTest extends \PHPUnit_Framework_TestCase
{
    public function testTranslatorFindsAndReturnsCommandHandler()
    {
        $translator = new NameBasedTranslator;

        $this->assertEquals($translator->translate(new DummyCommand), DummyHandler::class);
    }

    /**
     * @expectedException \HelpScout\Bus\Exceptions\HandlerNotRegisteredException
     */
    public function testTranslatorThrowsExceptionWhenHandlerDoesNotExist()
    {
        $translator = new NameBasedTranslator;

        $translator->translate(new FooCommand);
    }
}
