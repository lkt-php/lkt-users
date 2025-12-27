<?php

namespace Lkt\Users\Instances;

use Lkt\Users\Config\Controllers\LktPermissionController;
use Lkt\Users\Generated\GeneratedLktUserRole;

class LktUserRole extends GeneratedLktUserRole
{
    const COMPONENT = 'lkt-user-role';

    public function hasPermission(string $component, string $permission): bool
    {
        $haystack = $this->getPermissions();
        if (!isset($haystack[$component])) return false;
        if (!isset($haystack[$component][$permission])) return false;
        return $haystack[$component][$permission] === true;
    }

    public function preparePermissions(): array
    {
        $currentConfig = $this->getPermissions();
        if (!$currentConfig) $currentConfig = [];
        $permissions = LktPermissionController::getAllManageablePermission();
        $r = [];

        foreach ($permissions as $component => $perms) {
            $t = [];

            foreach ($perms as $perm) {
                $value = false;
                if (isset($currentConfig[$component])) $value = (bool)$currentConfig[$component][$perm] === true;
                $t[$perm] = $value;
            }

            $r[$component] = $t;
        }

        return $r;
    }
}