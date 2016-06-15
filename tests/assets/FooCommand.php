<?php
namespace HelpScout\Bus\Tests\Assets;

use HelpScout\Bus\Contracts\Command;
use HelpScout\Bus\WithDataTrait;

class FooCommand implements Command
{
    /**
     * Prefix for the command
     *
     * @var string
     */
    public $prefix;

    /**
     * Suffix for the commands
     *
     * @var string
     */
    public $suffix;

    use WithDataTrait;
}
