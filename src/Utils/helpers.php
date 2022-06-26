<?php

if (! function_exists('tap')) {
    /**
     * Call the given Closure with the given value then return the value.
     *
     * @template T
     * @param T $value
     * @param callable $callback
     * @return mixed
     */
    function tap(mixed $value, callable $callback): mixed
    {
        $callback($value);

        return $value;
    }
}