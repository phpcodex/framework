<?php

namespace PHPCodex\Framework\Support\Globalscope;

use PHPCodex\Framework\Traits\Support\SuperGlobals;

class SuperGlobalPost
{
    use SuperGlobals;

    /**
     * SuperGlobalPost constructor.
     */
    public function __construct()
    {
        $this->data = $_POST;
    }
}