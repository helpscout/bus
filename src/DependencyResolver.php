<?php
namespace HelpScout\Bus;

use HelpScout\Bus\Contracts\Handler;

/**
 * Class DefaultCommandBus
 *
 * @package HelpScout\Bus
 */
class DependencyResolver extends DefaultResolver
{
    /**
     * Create a handler with dependencies
     *
     * @param string $className
     *
     * @return Handler
     */
    protected function initClass($className)
    {
        // Use reflection to get the list of constructor dependencies
        $class       = new \ReflectionClass($className);
        $constructor = $class->getConstructor();

        // if no constructor, pop smoke and move out!
        if (! $constructor) {
            return $class->newInstance();
        }

        $parameters = $constructor->getParameters();

        // Fetch each of the dependencies from the factory, and init validators
        // via their fully namespaced name.
        $dependencies = [];
        foreach ($parameters as $parameter) {
            $dependencies[] = $this->initClass(
                $parameter->getClass()->getName()
            );
        }

        // Init the class with our list of introspected dependencies
        return $class->newInstanceArgs($dependencies);
    }
}
