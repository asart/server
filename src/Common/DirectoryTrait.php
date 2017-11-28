<?php

namespace App\Common;


trait DirectoryTrait
{
    public function mkDirIfNotExist($path)
    {
        if (!is_dir($path))
            mkdir($path, 0777, true);
    }
}