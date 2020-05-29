<?php

namespace App\Http\Controllers;

use App\Country;
use App\File;
use App\FileEntry;
use App\Operator;
use App\System;
use App\Timezone;
use App\Topup;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WizardController extends Controller
{
    public function index(){
        return view('dashboard.wizard.home', [
            'page' => [
                'type' => 'dashboard'
            ],
            'files' => Auth::user()['files']
        ]);
    }

    public function getTemplate(){
        return view('dashboard.wizard.template');
    }

    public function start ($id){
        $file = File::find($id);
        if ($file === null)
            return response()->json(['errors' => ['error' => 'File not found']],422);
        $user = Auth::user();
        if ($file['user_id'] !== $user['id'])
            return response()->json(['errors' => ['error' => 'Unauthorized Access']],422);
        return view('dashboard.wizard.confirm',[
            'page'=>['type' => 'dashboard'],
            'countries' => Country::all(),
            'file' => $file
        ]);
    }

    public function schedule($id){
        $file = File::find($id);
        if ($file === null)
            return response()->json(['errors' => ['error' => 'File not found']],422);
        $user = Auth::user();
        if ($file['user_id'] !== $user['id'])
            return response()->json(['errors' => ['error' => 'Unauthorized Access']],422);
        if ($file['status'] === 'START')
            return view('dashboard.wizard.schedule',[
                'page'=>['type' => 'dashboard'],
                'file' => $file,
                'timezones' => Timezone::all()
            ]);
        else
            return redirect('/wizard');
    }

    public function scheduleTopup($id, Request $request){
        $file = File::find($id);
        if ($file === null)
            return response()->json(['errors' => ['error' => 'File not found']],422);
        $user = Auth::user();
        if ($file['user_id'] !== $user['id'])
            return response()->json(['errors' => ['error' => 'Unauthorized Access']],422);
        if ((isset($request['schedule_now'])) && $request['schedule_now'] == 'true'){
            $timezone = Timezone::where('abbr','UTC')->first();
            $now = Carbon::now($timezone['utc'][0]);
            $dateTime = $now;
        }else {
            $request->validate([
                'timezone' => 'required',
                'date' => 'required',
                'time' => 'required'
            ]);
            $timezone = Timezone::find($request['timezone']);
            $dateTime = Carbon::parse($request['date'].' '.$request['time'], $timezone['utc'][0]);
            $now = Carbon::now($timezone['utc'][0]);
        }
        if ($timezone === null)
            return response()->json(['errors' => ['error' => 'Timezone not found']],422);
        if ($dateTime < $now)
            return response()->json(['errors' => ['error' => 'You cannot schedule in the past.']],422);
        if ($file['status'] !== 'START')
            return response()->json(['errors' => ['error' => 'File Already Processed']],422);
        foreach ($file['numbers'] as $number)
            Topup::create([
                'user_id' => $user['id'],
                'file_entry_id' => $number['id'],
                'timezone_id' => $timezone['id'],
                'scheduled_datetime' => $dateTime
            ]);
        $file['status'] = 'DONE';
        $file->save();
        return response()->json([
            'message' => 'File Scheduled.',
            'location' => '/topups'
        ]);
    }

    public function process($id, Request $request){
        $request->validate([
            'id' => 'required|array',
            'id.*' => 'required|distinct',
            'country' => 'required|array',
            'operator' => 'required|array',
            'is_local' => 'required|array',
            'is_local.*' => 'required|min:0|max:1',
            'amount' => 'required|array',
            'amount.*' => 'required',
            'number' => 'required|array',
            'number.*' => 'required'
        ],[
            'amount.*.required'=> 'Amount is Required',
            'is_local.*.min'=> 'Data seems to be corrupt. Please refresh page.',
            'is_local.*.max'=> 'Data seems to be corrupt. Please refresh page.',
        ]);
        $user = Auth::user();
        $file = File::find($id);
        if ($file === null)
            return response()->json(['errors' => ['error' => 'Invalid Request File.']],422);
        foreach ($request['id'] as $key => $id){
            $fileEntry = FileEntry::find($id);
            if ($fileEntry === null)
                return response()->json(['errors' => ['error' => 'Invalid Entry in Stack.']],422);
            $request->validate([
                'country.'.$key => 'required|int|min:1',
                'operator.'.$key => 'required|int|min:1',
                'is_local.'.$key => 'required|int|min:0|max:1',
                'amount.'.$key => 'required',
            ],[
                'country.*.min'=> 'Invalid Country Selected for Entry at '.($key+1),
                'operator.*.min'=> 'Invalid Operator Selected for Entry at '.($key+1),
            ]);
            $country = Country::find($request['country'][$key]);
            $operator = Operator::find($request['operator'][$key]);
            $isLocal = $request['is_local'][$key] == '1';
            $amount = $request['amount'][$key];
            $number = $request['number'][$key];
            if ($country)
                if ($operator && $operator['country_id'] === $country['id'])
                    if (!($isLocal && !$operator['supports_local_amounts']))
                        switch ($operator['denomination_type']) {
                            case 'FIXED':
                                $amounts = $operator['fixed_amounts'];
                                if ($isLocal)
                                    $amounts = $operator['local_fixed_amounts'];
                                $element = in_array($amount,$amounts);
                                    if ($element)
                                    {
                                        FileEntry::whereId($fileEntry['id'])->update([
                                            'country_id' => $country['id'],
                                            'operator_id' => $operator['id'],
                                            'is_local' => $isLocal,
                                            'amount' => $amount,
                                            'number' => $number
                                        ]);
                                    }
                                    else
                                        return response()->json(['errors' => ['error' => 'INVALID AMOUNT']],422);
                                    break;
                            case 'RANGE':
                                $min = $operator['min_amount'];
                                $max = $operator['max_amount'];
                                    if ($isLocal){
                                        $min = $operator['local_min_amount'];
                                        $max = $operator['local_max_amount'];
                                    }
                                    if ($amount >= $min)
                                        if ($amount <= $max)
                                        {
                                            FileEntry::whereId($fileEntry['id'])->update([
                                                'country_id' => $country['id'],
                                                'operator_id' => $operator['id'],
                                                'is_local' => $isLocal,
                                                'amount' => $amount,
                                                'number' => $number
                                            ]);
                                        }
                                        else
                                            return response()->json(['errors' => ['error' => 'AMOUNT < '.$max]],422);
                                    else
                                        return response()->json(['errors' => ['error' => 'AMOUNT > '.$min]],422);
                                    break;
                            default:
                                return response()->json(['errors' => ['error' => 'INVALID OPERATOR TYPE']],422);
                        }
                    else
                        return response()->json(['errors' => ['error' => 'OPERATOR DOES NOT SUPPORT LOCAL']],422);
                else
                    return response()->json(['errors' => ['error' => 'INVALID OPERATOR']],422);
            else
                return response()->json(['errors' => ['error' => 'INVALID COUNTRY']],422);
        }
        $balance = System::me()->getBalance();
        $total = $file['total_amount'];
        if ($total <= $balance)
            return response()->json([
                'message' => 'All Entries Saved. Proceeding to Next Step',
                'location' => '/wizard/schedule/file/'.$file['id']
            ]);
        else
            return response()->json(['errors' => [
                'Error' => 'Insufficient Balance<br><br>Required '.$total.' '.System::me()['reloadly_currency'].'<br>Available '.$balance
            ]],422);
    }

    public function deleteEntry($id){
        FileEntry::find($id)->delete();
        return response()->json(['message' => 'Successfully Deleted']);
    }
}
