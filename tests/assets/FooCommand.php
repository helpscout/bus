<?php
namespace HelpScout\Bus\Tests\Assets;

use HelpScout\Bus\Contracts\Command;
use HelpScout\Bus\WithDataTrait;

class FooCommand implements Command
{
    public $prefix;
    public $suffix;

    use WithDataTrait;
}
