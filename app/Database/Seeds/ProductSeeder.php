<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'name' => 'Laptop Dell XPS 13',
                'price' => 15000000,
                'stock' => 5,
                'description' => 'Laptop ultraportable dengan performa tinggi',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Monitor LG 27 inch',
                'price' => 3500000,
                'stock' => 10,
                'description' => 'Monitor 4K dengan refresh rate 60Hz',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Keyboard Mechanical RGB',
                'price' => 1200000,
                'stock' => 15,
                'description' => 'Keyboard gaming dengan switch mekanik',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Mouse Logitech MX Master',
                'price' => 800000,
                'stock' => 20,
                'description' => 'Mouse wireless dengan presisi tinggi',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Headphone Sony WH-1000XM5',
                'price' => 5500000,
                'stock' => 8,
                'description' => 'Headphone noise cancelling premium',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('products')->insertBatch($data);
    }
}
