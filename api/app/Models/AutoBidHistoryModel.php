<?php

namespace App\Models;

use CodeIgniter\Model;

class AutoBidHistoryModel extends Model
{
    protected $table = 'auto_bid_history';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'auction_id',
        'auction_item_id',
        'min_price',
        'status',
        'max_price',
        'flag',
        'buyer_id',
        'is_upcoming'
    ];
}
