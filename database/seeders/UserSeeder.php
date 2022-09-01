<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'id' => 1,
                'name' => 'Luiz Gustavo',
                'role' => 'shopkeeper',
                'cpf' => null,
                'cnpj' => '61.137.620/0001-61',
                'email' => 'luizgustavo@moneyflow.com.br',
                'password' => '$2y$10$H/QkdnCY30CaUZxEAMkBiet4I1wwrbJN1/dK6U7d9Eszeq1C1KFmS', // M4tr1x123
                'created_at' => now(),
                'updated_at' => now(),
                'email_verified_at' => now(),
            ],
            [
                'id' => 2,
                'name' => 'Maria Luiza',
                'role' => 'client',
                'cpf' => '803.851.650-80',
                'cnpj' => null,
                'email' => 'marialuiza@moneyflow.com.br',
                'password' => '$2y$10$H/QkdnCY30CaUZxEAMkBiet4I1wwrbJN1/dK6U7d9Eszeq1C1KFmS', // M4tr1x123
                'created_at' => now(),
                'updated_at' => now(),
                'email_verified_at' => now(),
            ],
            [
                'id' => 3,
                'name' => 'João Silva',
                'role' => 'shopkeeper',
                'cpf' => null,
                'cnpj' => '97.346.998/0001-12',
                'email' => 'joaosilva@moneyflow.com.br',
                'password' => '$2y$10$H/QkdnCY30CaUZxEAMkBiet4I1wwrbJN1/dK6U7d9Eszeq1C1KFmS', // M4tr1x123
                'created_at' => now(),
                'updated_at' => now(),
                'email_verified_at' => now(),
            ],
            [
                'id' => 4,
                'name' => 'Debora Nunes',
                'role' => 'client',
                'cpf' => '870.117.830-08',
                'cnpj' => null,
                'email' => 'deboranunes@moneyflow.com.br',
                'password' => '$2y$10$H/QkdnCY30CaUZxEAMkBiet4I1wwrbJN1/dK6U7d9Eszeq1C1KFmS', // M4tr1x123
                'created_at' => now(),
                'updated_at' => now(),
                'email_verified_at' => now(),
            ],
            [
                'id' => 5,
                'name' => 'Luiza Souza',
                'role' => 'shopkeeper',
                'cpf' => null,
                'cnpj' => '55.099.542/0001-55',
                'email' => 'luizasouza@moneyflow.com.br',
                'password' => '$2y$10$H/QkdnCY30CaUZxEAMkBiet4I1wwrbJN1/dK6U7d9Eszeq1C1KFmS', // M4tr1x123
                'created_at' => now(),
                'updated_at' => now(),
                'email_verified_at' => now(),
            ],
        ]);
    }
}
