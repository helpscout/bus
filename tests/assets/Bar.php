<?php
namespace HelpScout\Bus\Tests\Assets;

class Bar
{
    /**
     * @var Baz
     */
    public $baz;

    public function __construct(Baz $baz)
    {
        $this->baz = $baz;
    }
}
