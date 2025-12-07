<?php

namespace Lkt\Users\Instances;

use Lkt\Http\Router;
use Lkt\Users\Generated\GeneratedLktUser;
use Lkt\Users\Generated\LktUserQueryBuilder;

class LktUser extends GeneratedLktUser
{
    const COMPONENT = 'lkt-user';

    public function login(): static
    {
        $_SESSION['user'] = $this->getId();
        return $this;
    }

    public function logout(): static
    {
        $_SESSION['user'] = 0;
        session_destroy();
        return $this;
    }

    public static function getLogged(): static
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

        return static::getInstance($id, static::COMPONENT);
    }
}