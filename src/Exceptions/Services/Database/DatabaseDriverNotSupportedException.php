<?php

namespace PHPCodex\Framework\Exceptions\Services\Database;

use PHPCodex\Framework\Traits\Services\CustomException;

class DatabaseDriverNotSupportedException extends \Exception
{
    use CustomException;
}