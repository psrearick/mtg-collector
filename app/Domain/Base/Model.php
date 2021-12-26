<?php

namespace App\Domain\Base;

class Model extends \Illuminate\Database\Eloquent\Model
{
    const USERSCOPE = '';

    protected $guarded = ['id'];
}
