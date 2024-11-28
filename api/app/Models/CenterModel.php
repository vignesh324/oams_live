<?php 
namespace App\Models;
use CodeIgniter\Model;
 
class CenterModel extends BaseModel
{
    protected $table = 'center';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'code', 'state_id', 'city_id', 'area_id', 'address', 'created_by', 'updated_by', 'status'];

}