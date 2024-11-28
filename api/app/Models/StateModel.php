<?php 
namespace App\Models;
use CodeIgniter\Model;
 
class StateModel extends BaseModel
{
    protected $table = 'state';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'code', 'created_by', 'updated_by', 'status'];

}