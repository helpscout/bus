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

        $properties = get_object_vars($self);
        foreach (array_keys($properties) as $property) {
            if (!array_key_exists($property, $data)) {
                continue;
            }
            $self->$property = $data[$property];
        }

        return $self;
    }
}
