<?php

namespace PHPCodex\Framework\Support\Filters;

use PHPCodex\Framework\Traits\Support\Filters;

class SlashSafe
{

    use Filters;
    
    /**
     * @var null|string
     *
     * The string we're trying to protect against.
     */
    protected $string = null;

    public function __construct(string $input = null)
    {
        $this->string = addslashes($input);
    }

}