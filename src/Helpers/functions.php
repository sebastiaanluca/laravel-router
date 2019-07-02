<?php

if (! function_exists('class_uses_trait')) {
    /**
     * @param $class
     * @param $trait
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
