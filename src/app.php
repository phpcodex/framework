<?php

namespace PHPCodex\Framework;

use PHPCodex\Framework\Services\Route;

use PHPCodex\Framework\Support\Dotenv;
use PHPCodex\Framework\Support\PDO\FilePath;

class App
{
    static $app             = null;

    const DOTENV_FILENAME   = '.env';
    const HELPERS           = '/Support/Helpers.php';

    /**
     * @return null|App
     *
     * As this is our entry point for the entire application,
     * we must ensure we can only be run once. This is why
     * we are going to use a Singleton design pattern here.
     */
    public static function Instance()
    {
        /**
         * Ensure our Application is only EVER begun once.
         */
        if (self::$app === null)
        {
            self::$app = new App();
        }

        /**
         * Now return our Application.
         */
        return self::$app;
    }

    /**
     * @var string
     * The path of where our application has spawned from.
     */
    protected $path;

    private function __construct(?string $path = null)
    {
        /**
         * Set the path of our application.
         */
        $this->path     = new FilePath(__DIR__);

        /**
         * Load our helpers file.
         */
        include_once $this->path->get() . self::HELPERS;

        /**
         * Load our environment settings in. We should
         * load this from our parent directory.
         */
        $dotEnv = new FilePath($this->path->parent()->get() . App::DOTENV_FILENAME);
        Dotenv::load($dotEnv);

        /**
         * Route Handling.
         */
        $this->route    = new Route;
    }

    public function path()
    {
        return $this->path;
    }

}