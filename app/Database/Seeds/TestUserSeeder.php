<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class TestUserSeeder extends Seeder
{
    public function run()
    {
        // single fixed test/admin account
        $data = [
            'username' => 'admin',
            'email' => 'admin@example.com',
            'password' => password_hash('admin123', PASSWORD_DEFAULT),
            'role' => 'admin',
            'created_at' => date('Y-m-d H:i:s'),
        ];

        // insert only if not exists
        $exists = $this->db->table('user')->where('username', $data['username'])->countAllResults();
        if ($exists == 0) {
            $this->db->table('user')->insert($data);
        }
    }
}
