<?php

namespace App\Models;

use CodeIgniter\Model;

class InvoiceItemModel extends Model
{
    protected $table = 'invoice_item';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'invoice_id', 'each_net', 'auction_item_id', 'qty',
        'bid_price', 'inward_item_id', 'created_by',
        'created_at', 'updated_at', 'garden_name', 'warehouse_name',
        'grade_name', 'center_name', 'lot_no',
        'weight_gross', 'sample_quantity',
        'garden_id', 'grade_id', 'grade_type'
    ];
}
