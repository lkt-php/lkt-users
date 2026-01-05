<?php

namespace Lkt\Users\Config\Controllers;

use Lkt\Users\Enums\RoleCapability;

class LktPermissionController
{
    protected static $permissionsManagement = [];
    protected static $ensuredPermissions = [];

    public static function hasComponentRegistered(string $component): bool
    {
        return isset(static::$permissionsManagement[$component]) || isset(static::$ensuredPermissions[$component]);
    }

    public static function enablePermissionManagement(string $component, string|array $permission): void
    {
        if (!is_array(static::$permissionsManagement[$component])) static::$permissionsManagement[$component] = [];

        if (is_array($permission)) {
            foreach ($permission as $perm) {
                if (!in_array($perm, static::$permissionsManagement[$component])) static::$permissionsManagement[$component][] = $perm;
            }
            return;
        }
        if (!in_array($permission, static::$permissionsManagement[$component])) static::$permissionsManagement[$component][] = $permission;
    }

    public static function ensurePermission(string $component, string $permission, RoleCapability $capability = RoleCapability::Owned): void
    {
        if (!is_array(static::$ensuredPermissions[$component])) static::$ensuredPermissions[$component] = [];
        if (!in_array($permission, static::$ensuredPermissions[$component])) {
            static::$ensuredPermissions[$component][$permission] = $capability;
        }
    }

    public static function getAllManageablePermission(): array
    {
        return static::$permissionsManagement;
    }

    public static function getEnsuredPermission(string $component, string $permission): null|RoleCapability
    {
        if (!isset(static::$ensuredPermissions[$component])) return null;
        if (!isset(static::$ensuredPermissions[$component][$permission])) return null;
        return static::$ensuredPermissions[$component][$permission];
    }
}