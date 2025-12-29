<?php

namespace Lkt\Users\Instances;

use Lkt\Factory\Instantiator\Enums\CrudOperation;
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

    public function postProcessRead(array $response): array
    {
        if ($this->accessPolicy) {
            if ($this->accessPolicy->name === 'lkt-related') return $response;
        }
        $response['permissions'] = $this->convertPermissionsValueToTableFormat();
        return $response;
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

    public function convertPermissionsValueToTableFormat(): array
    {
        $haystack = $this->preparePermissions();
        $r = [];

        foreach ($haystack as $component => $perms) {
            $r[] = [
                'component' => $component,
                ...$perms,
            ];
        }

        return $r;
    }

    public function convertTablePermissionsToValueFormat(array $haystack): array
    {
        $r = [];
        foreach ($haystack as $item) {
            $t = $item;
            $component = $t['component'];
            unset($t['component']);
            $r[$component] = $t;
        }
        return $r;
    }

    public function prepareCrudData(array $data, CrudOperation|null $operation = null): array
    {
        $data['permissions'] = $this->convertTablePermissionsToValueFormat($data['permissions']);
        return $data;
    }
}