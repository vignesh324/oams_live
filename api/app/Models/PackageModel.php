<?php 
namespace App\Models;
use CodeIgniter\Model;
 
class PackageModel extends BaseModel
{
    protected $table = 'package';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'created_by', 'updated_by', 'status'];

}