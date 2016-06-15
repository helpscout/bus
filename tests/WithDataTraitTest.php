<?php
namespace HelpScout\Bus\Tests;

use HelpScout\Bus\Tests\Assets\FooCommand;

class WithDataTraitTest extends \PHPUnit_Framework_TestCase
{
    public function testWithDataSetsClassProperties()
    {
        $data    = [
            'prefix' => 'hello',
            'suffix' => 'world'
        ];
        $command = FooCommand::withData($data);

        self::assertEquals('hello', $command->prefix);
        self::assertEquals('world', $command->suffix);
    }

    public function testWithDataSkipsNonPropertyValues()
    {
        $data    = [
            'punctuation' => '!'
        ];
        $command = $command = FooCommand::withData($data);

        self::assertFalse(isset($command->punctuation));
    }
}
