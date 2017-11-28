<?php

namespace App\Service;

use ArrayAccess;
use App\Entity\Solve;
use BadMethodCallException;

class PhpResultReader implements ArrayAccess
{
    private $status;
    private $output;
    
    public function read($data)
    {
        $this->output = array_map(function ($line) { return preg_replace('/[[:blank:]]{2,}/', '', $line); }, $data);
        $this->output = array_values(array_filter($this->output, function ($line) { return !empty($line); }));

        if (preg_match("/\bOK\b/", end($data))) {
            $this->status = Solve::SOLVED;
        } else $this->status = Solve::UNSOLVED;

        return $this;
    }

    public function offsetSet($offset, $value)
    {
        throw new BadMethodCallException('Метод не доступен');
    }

    public function offsetExists($offset)
    {
        return property_exists(self::class, $offset);
    }

    public function offsetUnset($offset)
    {
        throw new BadMethodCallException('Метод не доступен');
    }

    public function offsetGet($offset)
    {
        return property_exists(self::class, $offset) ? $this->$offset : null;
    }
}