<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddTaxAdminCouponFields extends Migration
{
    public function up()
    {
        $this->forge->addColumn('transaction', [
            'ppn' => [
                'type'       => 'DOUBLE',
                'null'       => true,
                'default'    => null,
                'after'      => 'ongkir',
            ],
            'biaya_admin' => [
                'type'       => 'DOUBLE',
                'null'       => true,
                'default'    => null,
                'after'      => 'ppn',
            ],
            'kupon_code' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
                'default'    => null,
                'after'      => 'biaya_admin',
            ],
            'diskon_kupon' => [
                'type'       => 'DOUBLE',
                'null'       => true,
                'default'    => null,
                'after'      => 'kupon_code',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('transaction', ['ppn', 'biaya_admin', 'kupon_code', 'diskon_kupon']);
    }
}
