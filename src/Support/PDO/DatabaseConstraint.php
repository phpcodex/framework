<?php

namespace PHPCodex\Framework\Support\PDO;

class DatabaseConstraint
{
    protected $key;
    protected $operator;
    protected $value;

    public function __construct(string $key, string $operator, string $value)
    {
        $this->key      = $key;
        $this->operator = $operator;
        $this->value    = $value;
    }

    public function get()
    {
        return "`" . $this->key . "` " . $this->operator . " '" . $this->value. "'";
    }
}