<?php

namespace App\Models;

use CodeIgniter\Model;

class AuctionLotTimingsModel extends Model
{
    protected $table = 'auction_session_times';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'auction_item_id', 'auction_id','created_at','created_by','lot_set','start_time','end_time'
    ];
}
