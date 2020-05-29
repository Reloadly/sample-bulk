<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FileEntry extends Model
{
    protected $guarded = ['id'];
    protected $hidden = ['country_id','created_at','updated_at','file_id'];
    protected $with = ['country', 'operator'];
    protected $appends = ['operators'];

    public function country(){
        return $this->belongsTo('App\Country');
    }

    public function operator(){
        return $this->belongsTo('App\Operator');
    }

    public function getOperatorsAttribute(){
        return Operator::where('country_id',$this['country_id'])->get();
    }

    public function getEstimatesAttribute(){
        return [
            'amount' => round($this['is_local']?($this['amount'] / $this['operator']['fx_rate']):$this['amount'],2).' '.System::me()['reloadly_currency'],
            'topup' => round($this['is_local']?$this['amount']:($this['amount'] * $this['operator']['fx_rate']),2).' '.$this['operator']['destination_currency_code']
        ];
    }
}
