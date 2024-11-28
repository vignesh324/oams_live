<?php

namespace App\Models;
use CodeIgniter\Model;

class InwardReturnModel extends Model
{
    protected $table = 'inward_return';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'date','inward_id', 'inward_item_id', 'inward_invoice_no', 'reason',
        'created_by', 'updated_by','return_quantity'
    ];

    public function inwardItems()
    {
        return $this->join('inward_items', 'inward_items.inward_id = inward.id', 'left');
    }
}
