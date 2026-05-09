<?php

namespace App\Models; // Harus tepat seperti ini

use CodeIgniter\Model;

class UserModel extends Model // Nama class harus sama persis dengan nama file

{

    protected $table      = 'users'; // Sesuaikan dengan nama tabel di MySQL kamu

    protected $primaryKey = 'id';



    // Pastikan kolom-kolom ini ada di tabel database kamu

    protected $allowedFields = ['username', 'password', 'email', 'role'];
}
