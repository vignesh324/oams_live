<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductLogModel extends Model
{
    protected $table = 'product_log';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'description', 'user_id', 'created_at'
    ];
}
