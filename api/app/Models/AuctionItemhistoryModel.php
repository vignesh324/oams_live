<?php

namespace App\Models;

use CodeIgniter\Model;

class AuctionItemhistoryModel extends Model
{
    protected $table = 'auction_items_price_history';
    protected $primaryKey = 'id';
    protected $allowedFields = [
       'auction_id', 'auction_item_id', 'valuation_price', 'base_price',
        'reverse_price', 'high_price','created_at', 'updated_at','status'
    ];
}
