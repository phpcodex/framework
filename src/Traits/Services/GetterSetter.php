<?php

namespace PHPCodex\Framework\Traits\Services;

trait GetterSetter
{
    public function __construct($parameters = [])
    {
        $this->set($parameters);
    }

    public function set($parameters = [])
    {
        foreach ($parameters as $key => $val) {
            if (isset($this->{$key})) {
                $this->{$key} = $val;
            }
        }
    }

    public function get($key) {
        return $this->{$key};
    }
}