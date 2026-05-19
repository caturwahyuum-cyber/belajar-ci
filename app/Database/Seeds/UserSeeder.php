<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $table = $this->db->table('users');
        $table->whereIn('username', ['admin', 'manager', 'staf', 'user1', 'user2', 'april', 'wahyu', 'catur'])->delete();

        $data = [
            [
                'username' => 'admin',
                'email' => 'admin@example.com',
                'password' => password_hash('admin123', PASSWORD_DEFAULT),
                'role' => 'admin',
            ],
            [
                'username' => 'manager',
                'email' => 'manager@example.com',
                'password' => password_hash('manager123', PASSWORD_DEFAULT),
                'role' => 'manager',
            ],
            [
                'username' => 'staf',
                'email' => 'staf@example.com',
                'password' => password_hash('staf123', PASSWORD_DEFAULT),
                'role' => 'staf',
            ],
        ];

        $table->insertBatch($data);
    }
}
