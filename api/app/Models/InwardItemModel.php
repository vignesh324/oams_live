<?php

namespace App\Models;

use CodeIgniter\Model;

class InwardItemModel extends Model
{
    protected $table = 'inward_items';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'invoice_id', 'inward_id', 'grade_id', 'bag_type','no_of_bags','sno_from', 'sno_to',
        'weight_net', 'weight_tare', 'weight_gross', 'total_net', 'total_tare',
        'total_gross', 'status','return_status','bay', 'created_by', 'updated_by','is_assigned','is_addedtocart'
    ];
}
