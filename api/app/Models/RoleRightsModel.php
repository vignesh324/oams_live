<?php 
namespace App\Models;
use CodeIgniter\Model;
 
class RoleRightsModel extends Model
{
    protected $table = 'role_rights';
        protected $primaryKey = 'id';
    protected $allowedFields = ['role_id','module_id','create_permission','list_permission','update_permission','delete_permission', 'created_by', 'updated_by','created_at', 'updated_at'];

    public function roleRights() {
        return $this->hasMany('RoleRightsModel', 'role_id');
    }
}