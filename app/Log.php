<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $guarded = ['id'];
    protected $casts = ['response' => 'array'];
}
