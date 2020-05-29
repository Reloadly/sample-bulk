<?php

namespace App;

use App\Mail\GenericMailer;
use App\Traits\PaypalSystem;
use App\Traits\ReloadlySystem;
use App\Traits\StripeSystem;
use App\Traits\CurrencyLayerSystem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;

class System extends Model
{
    use ReloadlySystem;

    protected $guarded = ['id'];

    public static function me(){
        return System::first();
    }

    public static function sendEmail($to,$view,$data){
        try{
            Mail::to($to)->send(new GenericMailer($data,$view));
        }catch (\Exception $ex){

        }
    }

}
