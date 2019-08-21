<?php

declare(strict_types=1);

if (! function_exists('class_uses_trait')) {
    /**
     * @param object|string $class
     * @param object|string $trait
     *
     * @return bool
     */
    function class_uses_trait($class, $trait) : bool
    {
        $uses = array_flip(class_uses_recursive($class));

        if (is_object($trait)) {
            $trait = get_class($trait);
        }

        return isset($uses[$trait]);
    }
}
