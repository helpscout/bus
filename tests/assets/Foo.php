<?php
namespace HelpScout\Bus\Tests\Assets;

class Foo
{
    /**
     * Testing with a public property
     *
     * @var Bar
     */
    public $bar;

    public function __construct(Bar $bar)
    {
        $this->bar = $bar;
    }
}
