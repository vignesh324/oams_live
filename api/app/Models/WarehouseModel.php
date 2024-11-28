<?php

namespace App\Models;

use CodeIgniter\Model;

class WarehouseModel extends BaseModel
{
    protected $table = 'warehouse';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'name', 'code', 'state_id', 'city_id', 'area_id',
        'gst_no', 'fssai_no', 'address', 'tea_board_no',
        'created_by', 'updated_by', 'status'
    ];
}
