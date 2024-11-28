<?php

namespace App\Models;

use CodeIgniter\Model;

class AuctionItemModel extends Model
{
    protected $table = 'auction_items';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'inward_invoice_id',
        'inward_item_id',
        'auction_id',
        'valuation_price',
        'auction_quantity',
        'base_price',
        'reverse_price',
        'grade_id',
        'last_sold_price',
        'high_price',
        'lot_no',
        'lot_set',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by',
        'status',
        'is_sold',
        'auction_each_net',
        'is_withdrawn',
        'sample_quantity',
        'min_bid_added'
    ];
}
