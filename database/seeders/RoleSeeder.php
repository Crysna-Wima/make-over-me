<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [[
                'name' => 'admin',
            ],
            [
                'name' => 'Penyedia jasa mua',
            ],
            [
                'name' => 'Pencari jasa mua',
            ],
        ];

        foreach ($data as $key => $value) {
            \App\Models\Role::create($value);
        }

        $this->command->info('Berhasil menambahkan data role');
    }
}
