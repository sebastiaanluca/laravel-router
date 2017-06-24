<?php

if (! function_exists('class_uses_trait')) {
    function class_uses_trait($class, $trait) : bool
    {
        $uses = class_uses($class);

        if (! $uses) {
            return false;
        }

        $trait = is_object($trait) ? get_class($trait) : $trait;

        if (! in_array($trait, array_values($uses))) {
            return false;
        }

        return true;
    }
}
