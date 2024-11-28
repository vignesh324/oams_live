<?php 
namespace App\Models;
use CodeIgniter\Model;
 
class CityModel extends BaseModel
{
    protected $table = 'city';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'code', 'state_id', 'created_by', 'updated_by', 'status'];

}