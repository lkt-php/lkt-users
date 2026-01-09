<?php

namespace Lkt\Users\Instances;

use Lkt\Factory\Instantiator\Instances\AbstractInstance;
use Lkt\Http\Enums\AccessLevel;
use Lkt\Http\Router;
use Lkt\Users\Generated\GeneratedLktUser;
use Lkt\Users\Generated\LktUserQueryBuilder;
use Lkt\Users\Interfaces\SessionUserInterface;

class LktUser extends GeneratedLktUser implements SessionUserInterface
{
    const COMPONENT = 'lkt-user';

    public function hasAdminAccess(): bool
    {
        return $this->isAdministrator() || count($this->getAdminRolesData()) > 0;
    }

    public function signIn(): static
    {
        $_SESSION['user'] = $this->getId();
        return $this;
    }

    public function signOut(): static
    {
        $_SESSION['user'] = 0;
        session_destroy();
        return $this;
    }

    public static function authenticate(string $username, string $password): ?static
    {
        $query = static::getQueryCaller()
            ->andEmailEqual($username)
            ->andPasswordEqual($password);

        $user = static::getOne($query);

        if ($user) {
            if ($user->statusIsActive()) {
                $_SESSION['user'] = (int)$user?->getId();
                LktAuthenticationLog::logSuccessSignInAttempt($username, $user);

            } else {
                $_SESSION['user'] = 0;
                LktAuthenticationLog::logInvalidSignInAttempt($username, $password, $user);
            }

        } else {
            $_SESSION['user'] = 0;
            LktAuthenticationLog::logInvalidSignInAttempt($username, $password);
        }

        return $user;
    }

    public static function ableToSignUp(string $username): bool
    {
        $user = static::getOne(static::getQueryCaller()->andEmailEqual($username));
        return !is_object($user);
    }

    public static function getSignedInUserId(): int
    {
        $id = (int)$_SESSION['user'];
        $token = null;

        $bearerToken = Router::getBearerToken();
        if ($bearerToken) {
            $token = $bearerToken;
        }

        if ($token) {
            $builder = static::getQueryBuilder();
            if ($builder instanceof LktUserQueryBuilder) {
                $builder->andSessionTokenEqual($token);
            } else {
                $builder->andStringEqual('session_token', $token);
            }
            $user = static::getOne($builder);

            if ($user instanceof static && $user->getId() > 0) {
                $id = $user->getId();
            }
        }

        return $id;
    }

    public static function getSignedInUser(): ?static
    {
        $instance = static::getInstance(static::getSignedInUserId(), static::COMPONENT);
        if ($instance->isAnonymous()) return null;
        return $instance;
    }

    public static function signedIn(): bool
    {
        return static::getSignedInUserId() > 0;
    }

    public function changePassword(string $password): static
    {
        return $this
            ->setAccessPolicy('change-password')
            ->autoUpdate(['password' => $password])
            ->save();
    }

    public function hasAppPermission(string $component, string $permission, AbstractInstance|null $instance = null): bool
    {
        foreach ($this->getAppRolesData() as $role) {
            if ($role->hasPermission($component, $permission, $instance)) return true;
        }
        return false;
    }

    public function hasAdminPermission(string $component, string $permission, AbstractInstance|null $instance = null): bool
    {
        if (!$this->hasAdminAccess()) return false;
        foreach ($this->getAdminRolesData() as $role) {
            if ($role->hasPermission($component, $permission, $instance)) return true;
        }
        return false;
    }

    public function attemptToGrantPermissions(AccessLevel $accessLevel, string $component, array $permissions, AbstractInstance|null $instance = null): array
    {
        $r = [];

        // Test perms which must be tested before granted
        foreach ($permissions as $i => $grantedPerm) {

            // Determine which perms are gonna be tested
            // (it can be the array key (if string), or (if numeric array key) the array value or an array of string at array value)
            $testedPerm = is_numeric($i) ? $grantedPerm : $i;
            $testedPerms = [];
            if (is_array($testedPerm)) $testedPerms = $testedPerm;
            else $testedPerms[] = $testedPerm;

            // Determine which perms are gonna be granted
            // It can be a single perm or an array of perms
            $grantedPerms = [];
            if (is_array($grantedPerm)) $grantedPerms = $grantedPerm;
            else $grantedPerms[] = $grantedPerm;

            // Test permissions differently if it's an admin route or not
            if ($accessLevel === AccessLevel::OnlyAdminUsers) {
                foreach ($testedPerms as $testedPerm) {
                    if ($this->hasAdminPermission($component, $testedPerm, $instance)) {
                        foreach ($grantedPerms as $grantedPerm) $r[] = $grantedPerm;
                    }
                }
            } else {
                foreach ($testedPerms as $testedPerm) {
                    if ($this->hasAppPermission($component, $testedPerm, $instance)) {
                        foreach ($grantedPerms as $grantedPerm) $r[] = $grantedPerm;
                    }
                }
            }
        }

        return $r;
    }
}