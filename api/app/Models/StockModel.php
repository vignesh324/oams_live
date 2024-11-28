<?php 
namespace App\Models;
use CodeIgniter\Model;
 
class StockModel extends Model
{
    protected $table = 'stock';
    protected $primaryKey = 'id';
    protected $allowedFields = ['inward_item_id', 'inward_id', 'qty','warehouse_id','created_at', 'updated_at'];

}