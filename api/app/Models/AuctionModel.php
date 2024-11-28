<?php

namespace App\Models;

use CodeIgniter\Model;

class AuctionModel extends Model
{
    protected $table = 'auction';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'center_id', 'date', 'sale_no', 'start_time', 'end_time',
        'lot_count', 'session_time', 'created_at','reason', 'updated_at',
        'created_by', 'updated_by','status','is_publish','type','min_hour_over','last_log_value'
    ];

    public function auctionItems()
    {
        return $this->join('auction_items', 'auction_items.auction_id = auction.id', 'left');
    }
}
