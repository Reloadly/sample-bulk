<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Topup;

class SyncTopups extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:topups';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync topups with the Reloadly Platform';

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
        Topup::where('status','PENDING')->chunk(100, function($topups)
        {
            foreach ($topups as $topup)
            {
                $now = Carbon::now();
                $datetime = Carbon::parse($topup['scheduled_datetime'],$topup['timezone']['utc'][0]);
                if ($datetime <= $now)
                    $topup->sendTopup();
            }
        });
    }
}
