<?php

namespace Lkt\Users\Enums;

enum PerformedAuthAction: int
{
    case SignIn = 1;
    case SignOut = 2;

    public static function getChoiceOptions(): array
    {
        return [
            'signIn' => PerformedAuthAction::SignIn->value,
            'signOut' => PerformedAuthAction::SignOut->value,
        ];
    }
}
