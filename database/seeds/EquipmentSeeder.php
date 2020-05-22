<?php

use Illuminate\Database\Seeder;

class EquipmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();

        for ($i = 0; $i < 20; $i++) {
            DB::table('equipment')->insert([
                'name' => Str::random(10),
                'description' => $faker->paragraph(1),
                'address' => Str::random(10) . 'street',
                'postal_code' => "67200",
                'city' => "kokkola",
                'country' => "finland",
            ]);
        }
    }
}
