<?php

namespace Lkt\Users\Interfaces;

use Lkt\Users\Instances\LktUserRole;

interface SessionUserInterface
{
    public function getId(): int;

    public function signIn(): static;
    public function signOut(): static;

    public static function getSignedInUserId(): int;
    public static function getSignedInUser(): ?static;
    public static function signedIn(): bool;

    /** @return LktUserRole[] */
    public function getAppRolesData(): array;
    public function getAdminRolesData(): array;
}