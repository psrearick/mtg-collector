<?php

namespace App\Domain\Base;

class Model extends \Illuminate\Database\Eloquent\Model
{
    protected $guarded = ['id'];
}
