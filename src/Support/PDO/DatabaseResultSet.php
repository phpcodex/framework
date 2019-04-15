<?php

namespace PHPCodex\Framework\Support\PDO;

use PHPCodex\Framework\Traits\Support\Model;

use \IteratorAggregate;
use \ArrayIterator;

class DatabaseResultSet implements IteratorAggregate
{
    use Model;

    protected $next_id = 0;

    protected $data = [];

    public function add(DatabaseResult $result)
    {
        $this->data[$this->next_id] = $result;
        $this->next_id++;
    }

    public function getIterator()
    {
        return new ArrayIterator($this->data);
    }
}