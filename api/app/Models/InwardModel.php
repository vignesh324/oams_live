<?php

namespace App\Models;

use CodeIgniter\Model;

class InwardModel extends Model
{
    protected $table = 'inward';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'center_id', 'seller_id', 'garden_id', 'warehouse_id', 'nett_total_weight',
        'gp_no', 'gp_date', 'arrival_date', 'quantity', 'remark',
        'created_by', 'updated_by', 'status', 'gross_total_weight'
    ];

    public function inwardItems()
    {
        return $this->join('inward_items', 'inward_items.inward_id = inward.id', 'left');
    }
}
