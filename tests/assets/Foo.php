<?php
namespace HelpScout\Bus\Tests\Assets;

class Foo
{
    /**
     * @var Bar
     */
    public $bar;

    public function __construct(Bar $bar)
    {
        $this->bar = $bar;
    }
}
