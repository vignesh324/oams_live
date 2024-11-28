<?php 
namespace App\Models;
use CodeIgniter\Model;
 
class AuctionGardenOrderModel extends Model
{
    protected $table = 'auction_garden_order';
    protected $primaryKey = 'id';
    protected $allowedFields = ['auction_id','center_id','garden_id', 'order_seq','garden_grade'];

}