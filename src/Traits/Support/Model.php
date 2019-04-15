<?php

namespace PHPCodex\Framework\Traits\Support;

trait Model
{
    public function first()
    {
        return $this->data[0] ?? null;
    }
}