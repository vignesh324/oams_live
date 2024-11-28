<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class RoleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        $token = $session->get('access_token');
        $tokenRole = $session->get('access_token_role');
        $roleId = $session->get('role_id');
        $permissions = $session->get('permissions');

        // Validate token, role, and role_id
        if (empty($token) || empty($tokenRole) || empty($roleId) || $tokenRole != 'user' || $roleId != 2) {
            return redirect()->to('/user_login');
        }

        // Ensure both module_id and permission_type are provided
        if (empty($arguments) || count($arguments) < 2) {
            return redirect()->to('/access_denied');
        }

        [$moduleId, $permissionType] = $arguments;

        // Filter permissions to find the specified module_id
        $filteredPermissions = array_filter($permissions ?? [], function ($value) use ($moduleId) {
            return isset($value['module_id']) && $value['module_id'] == $moduleId;
        });
        // print_r($filteredPermissions);exit;
        if (empty($filteredPermissions) || empty($filteredPermissions[0]["{$permissionType}_permission"])) {
            return redirect()->to('/access_denied');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No after processing needed for this filter
    }
}
