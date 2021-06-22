<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class UsuariosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('usuarios')->delete();

		DB::table('usuarios')->insert(array (
			0 =>
			array (
				'id' => 1,
				'password' => bcrypt('7CVZqYVL90Sv'),
				'login' => 'admin',
                'api_key' => 'cf7fd68d-afe4-4606-83a3-969ec9ac416c',
				'created_at' => '2016-01-19 14:52:44',
				'updated_at' => '2016-01-28 17:08:53',
			),
		));
    }
}
