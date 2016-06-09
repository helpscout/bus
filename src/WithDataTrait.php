<?php

namespace HelpScout\Bus;

use HelpScout\Bus\Contracts\Command;

trait WithDataTrait
{
    /**
     * Init from name parameters
     *
     * @param array $data
     *
     * @return Command
     */
    public static function withData(array $data)
    {
        $self = new self();
        foreach ($self as $property => $value) {
            if (!array_key_exists($property, $data)) {
                continue;
            }
            $self->$property = $data[$property];
        }

        return $self;
    }
}
