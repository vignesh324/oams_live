<?php

if (!function_exists('has_module_permission')) {
    /**
     * Check if the user has a specific permission for a given module.
     *
     * @param int $moduleId The module ID to check permissions for.
     * @param string $permissionType The specific permission to check (e.g., 'update_permission').
     * @return bool True if permission exists, false otherwise.
     */
    function has_module_permission(int $moduleId, string $permissionType): bool
    {
        $permissions = session()->get('permissions') ?? [];

        // Find permissions for the specific module ID
        foreach ($permissions as $permission) {
            if ($permission['module_id'] == $moduleId && !empty($permission[$permissionType])) {
                return $permission[$permissionType] == 1;
            }
        }

        return false;
    }
}

if (!function_exists('render_permission_button')) {
    /**
     * Render a button with permission check.
     *
     * @param int $moduleId The module ID to check permissions for.
     * @param string $permissionType The specific permission to check (e.g., 'update_permission').
     * @param string $onclick The JavaScript function to call on click.
     * @param string $label The label for the button.
     * @param string $iconClass The FontAwesome class for the button icon.
     * @param string $class Additional classes for the button (default: 'btn btn-dark').
     * @param string|null $modalId The ID of the modal to open, if applicable (default: null).
     * @return string Rendered HTML for the button if permission exists, or an empty string otherwise.
     */

    if (!function_exists('render_add_button')) {
        /**
         * Render an "Add" button if the user has the required permission for a module.
         *
         * @param int $moduleId The module ID to check permissions for.
         * @param string $onclick The JavaScript function to call on click.
         * @return string Rendered HTML for the "Add" button if permission exists.
         */
        function render_add_button(int $moduleId, string $onclick): string
        {
            if (has_module_permission($moduleId, 'create_permission')) {
                return '<a href="#" class="btn btn-app float-sm-right" onclick="' . htmlspecialchars($onclick) . '">
                            <span class="badge bg-purple">New</span>
                            <i class="fas fa-users"></i> ADD
                        </a>';
            }
            return '';
        }
    }

    if (!function_exists('render_edit_button')) {
        /**
         * Render an "Edit" button if the user has the required permission for a module.
         *
         * @param int $moduleId The module ID to check permissions for.
         * @param string $onclick The JavaScript function to call on click.
         * @return string Rendered HTML for the "Edit" button if permission exists.
         */
        function render_edit_button(int $moduleId, string $onclick): string
        {
            if (has_module_permission($moduleId, 'update_permission')) {
                return '<a href="#" class="btn btn-dark-cyne edit_button" onclick="' . htmlspecialchars($onclick) . '" style="margin-right: 5px;" title="Edit">
                            <i class="fa fa-edit"></i>
                        </a>';
            }
            return '';
        }
    }

    if (!function_exists('render_delete_button')) {
        /**
         * Render a "Delete" button if the user has the required permission for a module.
         *
         * @param int $moduleId The module ID to check permissions for.
         * @param string $onclick The JavaScript function to call on click.
         * @return string Rendered HTML for the "Delete" button if permission exists.
         */
        function render_delete_button(int $moduleId, string $onclick): string
        {
            if (has_module_permission($moduleId, 'delete_permission')) {
                return '<a href="#" class="btn btn-dark-cyne delete_button" onclick="' . htmlspecialchars($onclick) . '" style="margin-right: 5px;" title="Delete">
                            <i class="fa fa-trash-alt"></i>
                        </a>';
            }
            return '';
        }
    }


    if (!function_exists('render_biddingsession_edit_button')) {
        /**
         * Render an "Edit" button if the user has the required permission for a module.
         *
         * @param int $moduleId The module ID to check permissions for.
         * @param string $onclick The JavaScript function to call on click.
         * @return string Rendered HTML for the "Edit" button if permission exists.
         */
        function render_biddingsession_edit_button(int $moduleId, string $onclick): string
        {
            if (has_module_permission($moduleId, 'update_permission')) {
                return '<a href="#" class="btn btn-dark-cyne edit_button" onclick="' . htmlspecialchars($onclick) . '" style="margin-right: 5px;" title="Edit">
                            <i class="fa fa-clock"></i>
                        </a>';
            }
            return '';
        }
    }
    if (!function_exists('render_biddingsession_close_button')) {
        /**
         * Render an "Edit" button if the user has the required permission for a module.
         *
         * @param int $moduleId The module ID to check permissions for.
         * @param string $onclick The JavaScript function to call on click.
         * @return string Rendered HTML for the "Edit" button if permission exists.
         */
        function render_biddingsession_close_button(int $moduleId, string $onclick): string
        {
            if (has_module_permission($moduleId, 'update_permission')) {
                return '<button title="Close Auction" onclick="' . htmlspecialchars($onclick) . '" class="btn btn-dark-cyne">
                                  <span><i class="fas fa-times"></i></span>
                        </button>';
            }
            return '';
        }
    }
}
