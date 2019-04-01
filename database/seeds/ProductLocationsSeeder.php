<?php

use App\Models\ProductLocation;
use Illuminate\Database\Seeder;

class ProductLocationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $product_locations = [
            [
                'name' => 'Product Location No. 1',
                'description' => 'Description for Product Location No. 1',
            ],
            [
                'name' => 'Product Location No. 2',
                'description' => 'Description for Product Location No. 2',
            ],
            [
                'name' => 'Product Location No. 3',
                'description' => 'Description for Product Location No. 3',
            ],
            [
                'name' => 'Product Location No. 4',
                'description' => 'Description for Product Location No. 4',
            ],
            [
                'name' => 'Product Location No. 5',
                'description' => 'Description for Product Location No. 5',
            ],
        ];

        foreach ($product_locations as $product_service) {
            factory(ProductLocation::class)->create([
                'name' => $product_service['name'],
                'description' => $product_service['description'],
            ]);
        }
    }
}
