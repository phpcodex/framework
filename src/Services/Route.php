<?php

namespace PHPCodex\Framework\Services;

use PHPCodex\Framework\Traits\Services\Route as RouteTrait;

class Route
{

    const ACCESS_VIA_CLI        = 'cli';
    const ACCESS_VIA_WEB        = 'web';

    const ROUTE_ARGV            = 'argv';
    const ROUTE_PATH_INFO       = 'PATH_INFO';
    const ROUTE_QUERY_STRING    = 'QUERY_STRING';

    use RouteTrait;

    const routes = [];

    /**
     * @var array
     * Values of where we are within our view system.
     */
    protected $path = [];

    /**
     * @var array
     * Values of our parameters in CLI.
     */
    protected $parameters = [];

    /**
     * @var array
     * Values of the query string we have attempted
     * following the ? within our URI.
     */
    protected $query = [];

    /**
     * @var string
     * This is the path that our Route would match upon.
     */
    protected $webPath;

    /**
     * @var Request
     * A place holder for our server globalscope variables
     * within our Request.
     */
    protected $request;

    public function __construct()
    {
        /**
         * Allow access to our server globalscope.
         */
        $this->request = new Request;

        /**
         * Obtain our SAPI name.
         */
        $this->sapi = php_sapi_name() == self::ACCESS_VIA_CLI ? self::ACCESS_VIA_CLI : self::ACCESS_VIA_WEB;

        /*
         * Obtain our parameters if we have any.
         */
        $this->getParameters();

        /**
         * Now load our route files.
         */
        $this->loadRouteFile();
    }

    private function getParameters() : void
    {
        /**
         * Identify what kind of viewer we have. This
         * will change our implementation of where
         * we obtain our query string data.
         */
        if ($this->sapi == self::ACCESS_VIA_CLI) {

            /**
             * We are accessing our framework via the CLI thus
             * we will only need the arguments passed in.
             */
            $pathInfo = $this->request->server(self::ROUTE_ARGV);
            array_shift($pathInfo);
            $this->parameters = $pathInfo;

        } elseif ($this->sapi == self::ACCESS_VIA_WEB) {

            /**
             * Capture the path sent through at the beginning
             * so we can later process this if required.
             */
            $this->webPath = $this->request->server(self::ROUTE_PATH_INFO) ?? '/';

            /**
             * Break down our web path so we can use the values
             * later when required.
             */
            $pathInfo = explode('/', $this->request->server(self::ROUTE_PATH_INFO) ?? null);

            array_shift($pathInfo);
            $this->path = $pathInfo;

            /**
             * Break down our query string key value pairs so
             * we can use this later too.
             */
            parse_str($_SERVER[self::ROUTE_QUERY_STRING], $queryString);
            $this->query = $queryString;

        }
    }

    public function loadRouteFile() : void
    {
        /**
         * Let's load our route file according to how we
         * are accessing the application.
         */
        $directory  = $this->sapi == self::ACCESS_VIA_WEB ? '../' : '';
        include ($directory . 'routes/' . $this->sapi . '.php');

        if ($this->matched == false) {
            echo 'failed any match';
        }
    }

}