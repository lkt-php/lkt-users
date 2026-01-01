<?php

namespace Lkt\Users\Instances;

use Lkt\Http\Router;
use Lkt\Users\Generated\GeneratedLktUser;
use Lkt\Users\Generated\LktUserQueryBuilder;

class LktUser extends GeneratedLktUser
{
    const COMPONENT = 'lkt-user';

    public function hasAdminAccess(): bool
    {
        return count($this->getAdminRolesData()) > 0;
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
}