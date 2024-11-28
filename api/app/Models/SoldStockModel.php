<?php 
namespace App\Models;
use CodeIgniter\Model;
 
class SoldStockModel extends Model
{
    protected $table = 'sold_stock';
    protected $primaryKey = 'id';
    protected $allowedFields = ['auction_id','auction_item_id',
    'inward_item_id', 'inward_id', 'qty','warehouse_id',
    'created_at', 'updated_at','garden_name', 'warehouse_name',
        'grade_name', 'center_name', 'lot_no',
        'weight_gross', 'sample_quantity',
        'garden_id', 'grade_id', 'grade_type'];

}