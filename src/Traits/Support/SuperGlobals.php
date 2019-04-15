<?php

namespace PHPCodex\Framework\Traits\Support;

/**
 * Trait SuperGlobals
 * @package PHPCodex\Framework\Traits\Support
 *
 * As our SuperGlobals functions are all going
 * to share the same Getter method, we should
 * implement a trate for this.
 */
trait SuperGlobals
{
    /**
     * @var array
     *
     * This will store all of our data
     */
    protected $data = [];

    /**
     * @param $key
     * @return mixed|null
     */
    public function get($key = null)
    {
        return $key != null ? $this->data[$key] ?? $key : $this->data;
    }
}