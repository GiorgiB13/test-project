<?php

namespace Database\Seeders;

use App\Models\Company;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::factory(10)->create();
        \App\Models\Company::factory(10)->create();
        $company = Company::find(1);
        $company->users()->sync([
            1 => ['date_at' => Carbon::now()],
            2 => ['date_at' => Carbon::now()],
            3 => ['date_at' => Carbon::now()]
        ]);
    }
}
