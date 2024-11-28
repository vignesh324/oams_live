<?php 
namespace App\Models;
use CodeIgniter\Model;
 
class SampleQuantityModel extends Model
{
    protected $table = 'sample_quantity';
    protected $primaryKey = 'id';
    protected $allowedFields = ['quantity', 'created_by', 'updated_by', 'status'];

}