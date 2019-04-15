<?php

namespace PHPCodex\Framework\Traits\Support;

trait Filters
{

    /**
     * @var
     *
     * The string we're protecting.
     */
    protected $string;

    /**
     * @return string
     *
     * Return the string we have ensured has passed
     * validation.
     */
    public function get() : string
    {
        return $this->string;
    }
}