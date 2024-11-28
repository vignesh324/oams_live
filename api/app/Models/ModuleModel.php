<?php 
namespace App\Models;
use CodeIgniter\Model;
 
class ModuleModel extends BaseModel
{
    protected $table = 'modules';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name','status', 'created_by', 'updated_by','created_at', 'updated_at'];

}