<?php

namespace App\Models;

use CodeIgniter\Model;

class BuyerModel extends BaseModel
{
    protected $table = 'buyer';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'name', 'code', 'state_id', 'city_id', 'area_id',
        'gst_no', 'fssai_no','token', 'address', 'tea_board_no','charges', 'contact_person_name',
        'contact_person_number','email','password','created_by', 'updated_by', 'status'
    ];
}
