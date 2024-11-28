<?php 
namespace App\Models;
use CodeIgniter\Model;
 
class HsnModel extends BaseModel
{
    protected $table = 'hsn';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'code', 'created_by', 'updated_by', 'status'];

}