<?php

namespace App\System;

final class Registry
{
    private static array $storage = [];

    // Prevent instance creation
    private function __construct()
    {
    }

    public static function get($key)
    {
        if (empty(self::$storage[$key])) {
            return null;
        }

        return self::$storage[$key];
    }

    /**
     * @throws \Exception
     */
    public static function set($key, $value)
    {
        if (isset(self::$storage[$key])) {
            throw new \Exception(sprintf('The key "%s" has been set already', $key));
        }

        self::$storage[$key] = $value;
    }

    public static function unset($key)
    {
        if (isset(self::$storage[$key])) {
            unset(self::$storage[$key]);
        }
    }
}