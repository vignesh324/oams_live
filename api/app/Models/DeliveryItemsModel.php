<?php

namespace App\Models;

use CodeIgniter\Model;

class DeliveryItemsModel extends Model
{
    protected $table = 'delivery_items';
    protected $primaryKey = 'id';
    protected $allowedFields = ['receipt_id', 'auction_id', 'is_sample_calc', 'auction_item_id', 'qty', 'created_at'];
}
