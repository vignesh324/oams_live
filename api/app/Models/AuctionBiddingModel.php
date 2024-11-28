<?php

namespace App\Models;

use CodeIgniter\Model;

class AuctionBiddingModel extends Model
{
    protected $table = 'auction_biddings';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'auction_item_id', 'buyer_id', 'bid_price', 'sq', 'bq','bid_type',
        'created_at', 'updated_at'
    ];
}
