<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Operator extends Model
{
    /**
     * The attributes that should be visible in arrays.
     *
     * @var array
     */


    protected $guarded = [ ];
    protected $casts = [
        'logo_urls' => 'array',
        'fixed_amounts' => 'array',
        'suggested_amounts' => 'array',
        'suggested_amounts_map' => 'array',
        'local_fixed_amounts' => 'array'
    ];

    public function country(){
        return $this->belongsTo('App\Country');
    }

    public function topups(){
        return $this->hasMany('App\Topup');
    }

    public function getFxForAmount($amount){
        $system = System::me();
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $system['reloadly_api_url']."/operators/fx-rate");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Content-Type:application/json",
            "Authorization: Bearer ".$system['reloadly_api_token']
        ));

        curl_setopt($ch,CURLOPT_POSTFIELDS,json_encode([
            'operatorId' => $this['rid'],
            'currencyCode' => $system['reloadly_currency'],
            'amount' => $amount
        ]));

        $response = curl_exec($ch);
        curl_close($ch);
        $response = json_decode($response);
        return isset($response->fxRate)?$response->fxRate:-1;
    }

}
