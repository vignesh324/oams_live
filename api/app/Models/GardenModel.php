<?php 
namespace App\Models;
use CodeIgniter\Model;
 
class GardenModel extends BaseModel
{
    protected $table = 'garden';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'vacumm_bag', 'code', 'state_id', 'city_id', 'area_id','category_id', 'seller_id', 'address', 'created_by', 'updated_by', 'status'];

}