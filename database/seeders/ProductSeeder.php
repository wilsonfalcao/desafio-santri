<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('products')->insert([
            [
                'name' => 'Parafusadeira Bosh Impacto 3000',
                'weight_grams' => 1000,
                'stock_quantity' => 10,
                'stock_uf' => 'PE',
                'price' => 350.0000
            ],
            [
                'name' => 'Travessa bake Oxford',
                'weight_grams' => 4000,
                'stock_quantity' => 20,
                'stock_uf' => 'PE',
                'price' => 188.8595
            ],
            [
                'name' => 'Aparelho de Jantar Oxford 20 Peças Donna Carolina',
                'weight_grams' => 3000,
                'stock_quantity' => 1,
                'stock_uf' => 'GO',
                'price' => 215.8700
            ],
        ]);
    }
}
