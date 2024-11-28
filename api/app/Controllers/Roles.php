<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\HTTP\Request;
use App\Models\ModuleModel;
use App\Models\RolesModel;
use App\Models\RoleRightsModel;
use CodeIgniter\API\ResponseTrait;
use Config\Services;


class Roles extends ResourceController
{
    use ResponseTrait;
    protected $session; // Define session as a class property

    public function __construct()
    {
        $this->session = Services::session(); // Initialize session in the constructor
    }
    public function index()
    {

        $db = \Config\Database::connect(); // Get the database connection

        $query = "
            SELECT 
            user_roles.id, 
            user_roles.role,
                (
                    SELECT GROUP_CONCAT(name ORDER BY name SEPARATOR ',') 
                    FROM modules 
                    WHERE modules.id IN (
                        SELECT module_id 
                        FROM role_rights 
                        WHERE role_rights.role_id = user_roles.id
                    )
                ) AS module_names
            FROM user_roles";

        $result = $db->query($query); // Execute the query

        // Fetch the result
        $rolesWithModules = $result->getResult();
        //echo '<pre>';print_r($rolesWithModules);exit;
        $model = new RolesModel();
        //$data['modules'] = $model->getRolesWithModules();
        $data['modules'] = $rolesWithModules;
        return $this->respond($data);
    }
    public function create()
    {

        $module_permissions = $this->request->getVar('module_view');
        $model = new ModuleModel();
        $roles_model = new RolesModel();
        $session_user_id = $this->request->getVar('session_user_id');
        // print_r($session_user_id);
        // exit;
        $rules = [
            'name' => 'required|regex_match[/^[a-zA-Z0-9][a-zA-Z0-9\s]*$/]|is_unique[package.name]'
        ];

        $messages = [
            "name" => [
                "required" => "Role Name is required.",
                'regex_match' => 'The Role Name field contains invalid characters.',
                'is_unique' => 'The Role Name field must be unique.',
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            $validationErrors = $this->validator->getErrors();
            return $this->fail($validationErrors, 422); // Bad request
        } else {
            $data = [
                'role' => $this->request->getVar('name'),
                'created_by' => $this->request->getVar('session_user_id'),
                'status' => 1,
            ];

            $role_id = $roles_model->insert($data);

            $role_id = $roles_model->insertID();
            foreach ($module_permissions as $key => $module) {
                $role_rights = new RoleRightsModel();
                $module = (array) $module;
                $module_data = [
                    'role_id' => $role_id,
                    'module_id' => $key,
                    'list_permission' => (isset($module[0])) ? 1 : 0,
                    'create_permission' => (isset($module[1])) ? 1 : 0,
                    'update_permission' => (isset($module[2])) ? 1 : 0,
                    'delete_permission' => (isset($module[3])) ? 1 : 0,
                    'created_by' => $this->request->getVar('session_user_id'),
                ];
                $inserted_module = $role_rights->insert($module_data);
            }


            $response = [
                'status' => 200,
                'error' => false,
                'message' => 'Inserted Successfully',
                'data' => $data
            ];

            return $this->respondCreated($response);
        }
    }

    public function show($id = null)
    {
        $model = new RolesModel();
        $role = new RoleRightsModel();
        $data['role_id'] = $model->where('id', $id)->first();
        $data['role_detail'] = $role->where('role_id', $id)->findAll();
        if ($data) {
            return $this->respond($data);
        } else {
            return $this->failNotFound('No Data Found with id : ' . $id);
        }
    }
    public function update($id = null)
    {
        $module_permissions = $this->request->getVar('module_view');
        $model = new ModuleModel();
        $roles_model = new RolesModel();

        $rules = [
            'name' => 'required|regex_match[/^[a-zA-Z0-9][a-zA-Z0-9\s]*$/]|is_unique[package.name,id,' . $id . ']'
        ];

        $messages = [
            "name" => [
                "required" => "Role Name is required.",
                'regex_match' => 'The Role Name field contains invalid characters.',
                'is_unique' => 'The Role Name field must be unique.',
            ],
        ];


        if (!$this->validate($rules, $messages)) {
            $validationErrors = $this->validator->getErrors();
            return $this->fail($validationErrors, 422); // Bad request
        } else {
            $existingRole = $roles_model->find($id);

            if (!$existingRole) {
                return $this->fail('Role not found', 404); // Not found
            }

            $data = [
                'role' => $this->request->getVar('name'),
                'updated_by' => $this->request->getVar('session_user_id'),
                'status' => 1,
            ];
            // print_r($data);exit;

            $roles_model->update($id, $data);


            // Update role rights

            $deleted_role_rights = new RoleRightsModel();
            $delete = $deleted_role_rights->where('role_id', $id)->delete();

            foreach ($module_permissions as $key => $module) {
                $role_rights = new RoleRightsModel();
                $module = (array) $module;
                $module_data = [
                    'module_id' => $key,
                    'role_id' => $id,
                    'list_permission' => (isset($module[0])) ? 1 : 0,
                    'create_permission' => (isset($module[1])) ? 1 : 0,
                    'update_permission' => (isset($module[2])) ? 1 : 0,
                    'delete_permission' => (isset($module[3])) ? 1 : 0,
                    'updated_by' => $this->request->getVar('session_user_id'),
                ];
                // print_r($key);
                // exit;

                $role_rights->insert($module_data);
            }


            $response = [
                'status' => 200,
                'error' => false,
                'message' => 'Updated Successfully',
                'data' => $data,
            ];
            return $this->respond($response);
        }
    }

    public function delete($id = null)
    {
        $model = new RolesModel();
        $deleted_role_rights = new RoleRightsModel();
        $data = $model->find($id);

        if ($data) {
            // Delete the role and associated role rights
            $model->delete($id);
            $delete = $deleted_role_rights->where('role_id', $id)->delete();

            $response = [
                'status' => 200,
                'error' => false,
                'message' => 'Role and associated role rights deleted successfully',
                'data' => $data
            ];

            return $this->respondDeleted($response);
        } else {
            return $this->failNotFound('No Data Found with id : ' . $id);
        }
    }
}
