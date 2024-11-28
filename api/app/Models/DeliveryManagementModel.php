<?php

namespace App\Models;

use CodeIgniter\Model;

class DeliveryManagementModel extends Model
{
    protected $table = 'delivery_receipt';
    protected $primaryKey = 'id';
    protected $allowedFields = ['receipt_no', 'invoice_id', 'auction_id', 'auction_item_id', 'date', 'qty', 'created_at'];
}
