<?php

namespace PHPCodex\Framework\Support;

use PHPCodex\Framework\Support\PDO\FilePath;

class Dotenv
{
    public static function load(FilePath $file) : void
    {
        /**
        if ($file->exists()) {

            $lines = file($file->get());

            foreach ($lines as $line) {
                if (trim($line) != '') {
                    putenv($line);
                }
            }
        }
         * */
    }
}