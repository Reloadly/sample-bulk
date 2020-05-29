<?php

namespace App\Console\Commands;

use App\System;
use Illuminate\Console\Command;

class SyncToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:tokens';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync Token from the Reloadly Platform';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $systems = System::where('reloadly_api_key','!=',null)->where('reloadly_api_secret','!=',null)->get();
        foreach ($systems as $system){
            $system['reloadly_api_token'] = $system->getToken();
            $system->save();
        }
    }
}
