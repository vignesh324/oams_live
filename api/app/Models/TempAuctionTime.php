<?php 
namespace App\Models;
use CodeIgniter\Model;
 
class TempAuctionTime extends Model
{
    protected $table = 'temp_auction_time';
    protected $primaryKey = 'id';
    protected $allowedFields = ['auction_id'];

}