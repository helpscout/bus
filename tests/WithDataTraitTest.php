<?php
namespace HelpScout\Bus\Tests;

use HelpScout\Bus\Tests\Assets\FooCommand;

class WithDataTraitTest extends \PHPUnit_Framework_TestCase
{
    public function testWithDataSetsClassProperties()
    {
        $command = FooCommand::withData([
            'prefix' => 'hello',
            'suffix' => 'world'
        ]);

        $this->assertEquals('hello', $command->prefix);
        $this->assertEquals('world', $command->suffix);
    }

    public function testWithDataSkipsNonPropertyValues()
    {
        $command = $command = FooCommand::withData([
            'punctuation' => '!'
        ]);

        $this->assertFalse(isset($command->punctuation));
    }
}
