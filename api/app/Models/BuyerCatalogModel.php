<?php 
namespace App\Models;
use CodeIgniter\Model;
 
class BuyerCatalogModel extends Model
{
    protected $table = 'buyer_catalog';
    protected $primaryKey = 'id';
    protected $allowedFields = ['auction_id','buyer_id', 'auction_item_id', 'status','is_deleted','created_by', 'updated_by'];

}