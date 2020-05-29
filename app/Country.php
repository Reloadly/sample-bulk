<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $guarded = ['id'];
    protected $casts = [ 'calling_codes' => 'array' ];
    protected $hidden = ['created_at', 'updated_at', 'calling_codes'];

    public  function operators(){
        return $this->hasMany('App\Operator');
    }
}
