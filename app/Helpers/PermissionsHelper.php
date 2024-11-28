<?php

if (!function_exists('has_module_permission')) {
    /**
     * Check if the user has a specific permission for a given module.
     *
     * @param int $moduleId The module ID to check permissions for.
     * @param string $permissionType The specific permission to check (e.g., 'create_permission').
     * @return bool True if permission exists, false otherwise.
     */
    function has_module_permission(int $moduleId, string $permissionType): bool
    {
        $permissions = session()->get('permissions') ?? [];
        
        // Filter the permissions array to find the matching module_id
        $filteredPermissions = array_filter($permissions, function ($value) use ($moduleId) {
            return isset($value['module_id']) && $value['module_id'] == $moduleId;
        });

        // Convert to indexed array and check for the specific permission
        $modulePermission = array_values($filteredPermissions);
        
        return !empty($modulePermission[0][$permissionType]) && $modulePermission[0][$permissionType] == 1;
    }
}

if (!function_exists('render_permission_button')) {
    /**
     * Render a button if the user has the required permission for a module.
     *
     * @param int $moduleId The module ID to check permissions for.
     * @param string $permissionType The specific permission to check (e.g., 'create_permission').
     * @param string $onclick The JavaScript function to call on click (e.g., 'add_state()').
     * @param string $modalId The ID of the modal to open (default is '#modal-sm').
     * @return string Rendered HTML for the button if permission exists, or an empty string otherwise.
     */
    function render_permission_button(int $moduleId, string $permissionType, string $onclick, string $modalId = '#modal-sm'): string
    {
        if (has_module_permission($moduleId, $permissionType)) {
            return '<a href="#" class="btn btn-app float-sm-right" onclick="' . $onclick . '" data-toggle="modal" data-target="' . $modalId . '">
                        <span class="badge bg-purple">New</span>
                        <i class="fas fa-users"></i> ADD
                    </a>';
        }
        return '';
    }
}
