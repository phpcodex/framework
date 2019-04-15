<?php

namespace PHPCodex\Framework\Support\PDO;

class DatabaseResult
{
    public function __construct(array $input)
    {
        foreach ($input as $key => $val) {
            $this->$key = $val;
        }
    }
}