<?php

use App\Models\ProductService;
use Illuminate\Database\Seeder;

class ProductServicesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $product_services = [
            [
                'name' => 'Product Service No. 1',
                'description' => 'Description for Product Service No. 1',
            ],
            [
                'name' => 'Product Service No. 2',
                'description' => 'Description for Product Service No. 2',
            ],
            [
                'name' => 'Product Service No. 3',
                'description' => 'Description for Product Service No. 3',
            ],
            [
                'name' => 'Product Service No. 4',
                'description' => 'Description for Product Service No. 4',
            ],
            [
                'name' => 'Product Service No. 5',
                'description' => 'Description for Product Service No. 5',
            ],
        ];

        foreach ($product_services as $product_service) {
            factory(ProductService::class)->create([
                'name' => $product_service['name'],
                'description' => $product_service['description'],
            ]);
        }
    }
}
