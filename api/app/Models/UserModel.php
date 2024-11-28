<?php 
namespace App\Models;
use CodeIgniter\Model;
 
class UserModel extends Model
{
    protected $table = 'user';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'username', 'password', 'email', 'phone', 'role_id','token', 'created_by', 'updated_by', 'status'];


}