<?php

use PHPCodex\Framework\Services\View;
use PHPCodex\Framework\Services\Database;

/**
 *
 */
if (!function_exists('DB')) {
    function DB($query)
    {
        return (new Database);
    }
}

/**
 * When we use a view, we should ensure that
 * we reference the view class. This opens
 * options for us later with extensibility.
 */
if (!function_exists('view')) {
    function view(string $resource, array $parameters = [])
    {
        return (new View($resource, $parameters))->get();
    }
}

/**
 * We need a way to read an environment file
 * so we can make changes within our system
 * without making changes to code.
 */
if (! function_exists('env')) {

    function env($key, $default = null)
    {
        return getenv($key ?? $default);
    }
}

/**
 * We should find a way to read data from a
 * configuration file that is in a fixed
 * location.
 *
 * As these helper functions have not namespace
 * context, we are unable to throw our FilePath
 * object around.
 */
if (! function_exists('config')) {

    function config($config, $default = null)
    {
        $info = explode('.', $config);
        $file = array_shift($info);

        $paths = ['./', '../'];

        foreach ($paths as $path) {
            if (file_exists($path . 'config/' . $file . '.php')) {
                $data = include $path . 'config/' . $file . '.php';

                while ($next = array_shift($info)) {
                    if (isset($data[$next])) {
                        $data = $data[$next];
                    } else {
                        $data = $default;
                    }
                }
            }
        }

        return $data;
    }
}