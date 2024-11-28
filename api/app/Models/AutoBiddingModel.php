<?php

namespace App\Models;

use CodeIgniter\Model;

class AutoBiddingModel extends Model
{
    protected $table = 'auto_bidding';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'auction_id', 'auction_item_id','min_price', 'max_price', 'buyer_id'
    ];
}
