<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('accounts')->insert([
            [
                'id' => 1,
                'account_key' => 'luizgustavo@moneyflow.com.br',
                'user_id' => 1,
                'balance' => 1000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'account_key' => 'marialuiza@moneyflow.com.br',
                'user_id' => 2,
                'balance' => 1000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'account_key' => 'joaosilva@moneyflow.com.br',
                'user_id' => 3,
                'balance' => 1000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 4,
                'account_key' => 'deboranunes@moneyflow.com.br',
                'user_id' => 4,
                'balance' => 1000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 5,
                'account_key' => 'luizasouza@moneyflow.com.br',
                'user_id' => 5,
                'balance' => 1000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
