<?php

namespace Database\Seeders;
use Illuminate\Support\Carbon;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AccountsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $now = Carbon::now();
    
        $accounts = [
            ['account_name' => 'Asset Accounts', 'created_at' => $now, 'updated_at' => $now],
            ['account_name' => 'Liability Accounts', 'created_at' => $now, 'updated_at' => $now],
            ['account_name' => 'Revenue Accounts', 'created_at' => $now, 'updated_at' => $now],
            ['account_name' => 'Equity Accounts', 'created_at' => $now, 'updated_at' => $now],
            ['account_name' => 'Expense Accounts', 'created_at' => $now, 'updated_at' => $now],
        ];
    
        DB::table('accounts')->insert($accounts);
    }
}
