<?php

namespace PHPCodex\Framework\Exceptions\Services\Database;

use PHPCodex\Framework\Traits\Services\CustomException;

class DatabaseDriverNotInstalledException extends \Exception
{
    use CustomException;
}