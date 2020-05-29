<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Timezone extends Model
{
    protected $casts = ['utc' => 'array'];
}
