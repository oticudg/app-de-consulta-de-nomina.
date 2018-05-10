<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('eventos')->insert([
            'event' => 'Entrega 1',
            'created_at' => \Carbon::now(),
            'updated_at' => \Carbon::now(),
        ]);
        \DB::table('eventos')->insert([
            'event' => 'Entrega 2',
            'created_at' => \Carbon::now(),
            'updated_at' => \Carbon::now(),
        ]);
    }
}
