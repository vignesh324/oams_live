<?php 
namespace App\Models;
use CodeIgniter\Model;
use CodeIgniter\Database\Query;
use CodeIgniter\Database\ConnectionInterface;

class RolesModel extends BaseModel
{
    protected $table = 'user_roles';
    protected $primaryKey = 'id';
    protected $allowedFields = ['role','status', 'created_by', 'updated_by','created_at', 'updated_at'];

    public function getRolesWithModules()
    {
        $builder = $this->db->table($this->table);
        $builder->select('roles.role_id, roles.role_name');

        $roleRightsBuilder = $this->db->table('roleRights');
        
        $subQuery = $this->db->table('module')
            ->select("GROUP_CONCAT(module_name ORDER BY module_name SEPARATOR ',') AS module_names")
            ->whereIn('module_id', function (Query $subBuilder) use ($roleRightsBuilder) {
                $subBuilder->select('module_id')
                    ->from($roleRightsBuilder)
                    ->where('roleRights.role_id = roles.role_id');
            })
            ->getCompiledSelect();

        $builder->select('(' . $subQuery . ')', false);
        $result = $builder->get();

        return $result->getResult();
    }

}