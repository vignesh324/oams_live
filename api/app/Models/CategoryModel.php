<?php 
namespace App\Models;
use CodeIgniter\Model;
 
class CategoryModel extends BaseModel
{
    protected $table = 'category';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'code', 'created_by', 'updated_by', 'status'];

}