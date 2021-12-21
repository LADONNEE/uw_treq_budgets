<?php

namespace App\Models;

class ReadOnlyModel extends Model
{
    public $incrementing = false;
    public $timestamps = false;

    public function save(array $options = [])
    {
        return false;
    }

    public function delete()
    {
        return false;
    }

}
