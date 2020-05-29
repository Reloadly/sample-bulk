<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Topup extends Model
{
    protected $guarded = ['id'];
    protected $casts = [
        'response' => 'array',
        'pin' => 'array'
    ];

    public function user(){
        return $this->belongsTo('App\User');
    }

    public function timezone(){
        return $this->belongsTo('App\Timezone');
    }
    public function file_entry(){
        return $this->belongsTo('App\FileEntry');
    }

    public function sendTopup(){
        $system = System::me();
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $system['reloadly_api_url']."/topups");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Content-Type:application/json",
            "Authorization: Bearer ".$system['reloadly_api_token']
        ));

        curl_setopt($ch,CURLOPT_POSTFIELDS,json_encode([
            'recipientPhone' => [
                'countryCode' => $this['file_entry']['operator']['country']['iso'],
                'number' => $this['file_entry']['number']
            ],
            'operatorId' => $this['file_entry']['operator']['rid'],
            'amount' => $this['file_entry']['amount'],
            'useLocalAmount' => $this['file_entry']['is_local']
        ]));

        $response = curl_exec($ch);
        curl_close($ch);
        \App\Log::create([
            'task' => 'SEND_TOPUP',
            'params' => 'TOPUP_ID:'.$this['id'].' PHONE:'.$this['number'].' TOPUP:'.$this['topup'],
            'response' => $response
        ]);
        $this['response'] = json_decode($response);
        if (isset($this['response']['errorCode']) && $this['response']['errorCode'] != null && $this['response']['errorCode'] != '')
            $this['status'] = 'FAIL';
        else{
            $this['status'] = 'SUCCESS';
            if (isset($this['response']['pinDetail']))
                $this['pin'] = $this['response']['pinDetail'];
        }
        $this->save();
    }

    public function getTransactionIdAttribute(){
        return isset($this['response']['transactionId'])?$this['response']['transactionId']:'NOT_AVAILABLE';
    }
}
