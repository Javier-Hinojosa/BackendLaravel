<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class ProductTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        $catrories= DB::table("category")->pluck("id")->toArray();
        if(empty($catrories)){
            $this->command->warn("No hay categorias, por favor ejecutar primero el seeder de categorias");
            return;
        }
        $products = [];
        for ($i= 0; $i <= 50; $i++) {
            $products[] = [
                'name' => $faker->word,
                'description' => $faker->sentence,
                'price' => $faker->randomFloat(2,10,500),
                'category_id' =>  $faker->randomElement($catrories),
                //$catrories[array_rand($catrories)], // Asignar una categoria aleatoria dentro de las existentes
                'created_at' => now(),
                'updated_at'=> now()
            ];
        }
        DB::table('product')->insert($products);
    }
}
