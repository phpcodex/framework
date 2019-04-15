<?php

namespace PHPCodex\Framework\Traits\Services;

use \Throwable;

trait CustomException
{
    /**
     * CustomException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(string $message = "", int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);

        /**
         * Here we could have some reporting throwing our errors
         * back to Sentry, DataDog or another communication layer.
         *
         * I've only pushed this into a trait to reduce the lines
         * of code required to achieve the same thing but if this
         * was a real-world project, this would be defined to
         * send error reports somewhere and not just log files.
         */
    }
}