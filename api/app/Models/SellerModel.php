<?php 
namespace App\Models;
use CodeIgniter\Model;
 
class SellerModel extends BaseModel
{
    protected $table = 'seller';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'code', 'seller_prefix', 'seller_suffix', 'state_id','tea_board_no','charges', 'city_id', 'area_id', 'gst_no', 'fssai_no', 'address', 'created_by', 'updated_by', 'status'];

}