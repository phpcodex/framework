<?php

namespace PHPCodex\Framework\Support\Globalscope;

use PHPCodex\Framework\Traits\Support\SuperGlobals;

class SuperGlobalGet
{
    use SuperGlobals;

    /**
     * SuperGlobalGet constructor.
     */
    public function __construct()
    {
        $this->data = $_GET;
    }
}