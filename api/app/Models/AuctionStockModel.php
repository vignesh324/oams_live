<?php 
namespace App\Models;
use CodeIgniter\Model;
 
class AuctionStockModel extends Model
{
    protected $table = 'auction_stock';
    protected $primaryKey = 'id';
    protected $allowedFields = ['auction_id','auction_item_id','inward_item_id', 'inward_id', 'qty','warehouse_id','created_at', 'updated_at'];

}