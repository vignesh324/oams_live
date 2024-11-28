<?php 
namespace App\Models;
use CodeIgniter\Model;
 
class CartModel extends Model
{
    protected $table = 'cart';
    protected $primaryKey = 'id';
    protected $allowedFields = ['auction_id','inward_id', 'sample_quantity', 'inward_item_id', 'qty','garden_id','created_by', 'updated_by'];

}