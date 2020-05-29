<?php

use Illuminate\Database\Seeder;
use App\System;

class SystemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (System::find(1) === null)
            System::updateOrCreate(['id' => 1],[
                'full_logo' => '/assets/svgs/logo.svg',
                'icon_logo' => '/assets/images/icon.png',
                'text_logo' => '/assets/svgs/logo_text.svg'
            ]);
    }
}
