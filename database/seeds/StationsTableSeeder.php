<?php

use Illuminate\Database\Seeder;

class StationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('stations')->insert([
            'name'=> '雅典',
        ]);

        DB::table('stations')->insert([
            'name'=> '菲基斯',
        ]);

        DB::table('stations')->insert([
            'name'=> '阿卡迪亞',
        ]);

        DB::table('stations')->insert([
            'name'=> '斯巴達',
        ]);
    }
}
