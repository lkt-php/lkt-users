<?php

namespace Lkt\Users\Instances;

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
}