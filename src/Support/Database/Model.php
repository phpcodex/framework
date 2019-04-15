<?php

namespace PHPCodex\Framework\Support\Database;

use PHPCodex\Framework\Services\Database;
use PHPCodex\Framework\Support\PDO\DatabaseConstraint;
use PHPCodex\Framework\Traits\Support\Model as ModelTrait;

class Model
{
    use ModelTrait;

    protected $database;
    protected $table;

    protected $primaryKey = 'id';

    protected $attributes = [];
    protected $fillable = [];

    public function __construct($attributes = [])
    {
        $database = new Database($this->database);
        $result = $database->query('show fields from ' . $this->getModel());

        foreach ($result as $column) {
            if ($column->Key != 'PRI') {
                $this->attributes[$column->Field] = $column->Default;
            } else {
                $this->primaryKey = $column->Field;
            }
        }

        foreach ($attributes as $key => $val) {
            if ($key != $this->primaryKey) {
                $this->attributes[$key] = $val;
            }
        }
    }

    private function getModel()
    {
        $class = array_pop(explode('\\', get_called_class()));
        $model  = $this->table ?? strtolower($class . 's');

        return $model;
    }

    public function where(string $key, string $operator, string $value)
    {
        $database = new Database($this->database);
        $constraint = new DatabaseConstraint($key, $operator, $value);

        $result = $database->query('SELECT * FROM `' . $this->getModel() . '` WHERE ' . $constraint->get());

        return $result;
    }

    public function save()
    {
        $database = new Database($this->database);

        $keys = implode("`, `", array_keys($this->attributes));
        $vals = '?' . str_repeat(', ?', count($this->attributes)-1);

        $database->query('INSERT INTO `'. $this->getModel() . '` (`'. $keys . '`) VALUES ('. $vals .')',
            $this->attributes
        );

        $result = $database->query(
            'SELECT '.$this->primaryKey.' FROM '.$this->getModel().' ORDER BY '.$this->primaryKey.' DESC LIMIT 1'
        )->first();

        $result = $this->where($this->primaryKey, '=', $result->{$this->primaryKey});

        foreach ($result->first() as $key => $val) {
            $this->{$key} = $val;
        }

        return $this;
    }

}