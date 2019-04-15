<?php

namespace PHPCodex\Framework\Services;

use PHPCodex\Framework\Support\Globalscope\SuperGlobalGet;
use PHPCodex\Framework\Support\Globalscope\SuperGlobalPost;
use PHPCodex\Framework\Support\Globalscope\SuperGlobalServer;

class Request
{
    protected $get;
    protected $post;
    protected $server;

    /**
     * Request constructor.
     *
     * This service just ties together our Superglobals
     * together in one nice and tidy place.
     */
    public function __construct()
    {
        $this->server   = new SuperGlobalServer();
        $this->get      = new SuperGlobalGet();
        $this->post     = new SuperGlobalPost();
    }

    /**
     * While what we are doing below is quite repetitive,
     * this is so we call each layer individually and can
     * perform their own validations if required.
     *
     * If we want to prevent XSS over Post, we don't do
     * that here, we do this in the SuperGlobalsPost
     * section. This Service just merely combines them
     * together as to create a service.
     */

    /**
     * @param null $key
     * @return mixed|null
     */
    public function get($key = null)
    {
        return $this->get->get($key);
    }

    /**
     * @param null $key
     * @return mixed|null
     */
    public function post($key = null)
    {
        return $this->post->get($key);
    }

    /**
     * @param null $key
     * @return mixed|null
     */
    public function server($key = null)
    {
        return $this->server->get($key);
    }
}