<?php 
namespace App\Models;
use CodeIgniter\Model;
 
class GradeModel extends BaseModel
{
    protected $table = 'grade';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'code',  'category_id', 'type', 'created_by', 'updated_by', 'status'];

}