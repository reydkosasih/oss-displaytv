<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SuperadminSeeder extends Seeder
{
    public function run()
    {
        $email = 'superadmin@displaytv.com';
        $user  = $this->db->table('users')->where('email', $email)->get()->getRow();

        if (!$user) {
            $data = [
                'name'       => 'Superadmin Display TV',
                'email'      => $email,
                'password'   => password_hash('admin123', PASSWORD_BCRYPT),
                'role'       => 'superadmin',
                'is_active'  => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ];

            $this->db->table('users')->insert($data);
        }
    }
}
