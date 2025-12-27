<?php

namespace Lkt\Users\Config\Controllers;

class LktPermissionController
{
    protected static $permissionsManagement = [];
    protected static $ensuredPermissions = [];

    public static function enablePermissionManagement(string $component, string $permission): void
    {
        if (!is_array(static::$permissionsManagement[$component])) static::$permissionsManagement[$component] = [];
        if (!in_array($permission, static::$permissionsManagement[$component])) static::$permissionsManagement[$component][] = $permission;
    }

    public static function ensurePermission(string $component, string $permission): void
    {
        if (!is_array(static::$ensuredPermissions[$component])) static::$ensuredPermissions[$component] = [];
        if (!in_array($permission, static::$ensuredPermissions[$component])) static::$ensuredPermissions[$component][] = $permission;
    }

    public static function getAllManageablePermission(): array
    {
        return static::$permissionsManagement;
    }
}