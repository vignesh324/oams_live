<?php 
namespace App\Models;
use CodeIgniter\Model;
 
class AreaModel extends BaseModel
{
    protected $table = 'area';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'code', 'state_id', 'city_id', 'created_by', 'updated_by', 'status'];

}