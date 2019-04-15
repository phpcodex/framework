<?php

namespace PHPCodex\Framework\Support\Globalscope;

use PHPCodex\Framework\Traits\Support\SuperGlobals;

class SuperGlobalServer
{
    use SuperGlobals;

    /**
     * SuperGlobalServer constructor.
     */
    public function __construct()
    {
        $this->data = $_SERVER;
    }

}