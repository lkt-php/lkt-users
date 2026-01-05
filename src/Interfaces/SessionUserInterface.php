<?php

namespace Lkt\Users\Interfaces;

use Lkt\Factory\Instantiator\Instances\AbstractInstance;

interface SessionUserInterface
{
    public function getId(): int;

    public function signIn(): static;
    public function signOut(): static;

    public static function getSignedInUserId(): int;
    public static function getSignedInUser(): ?static;
    public static function signedIn(): bool;
    public function hasAppPermission(string $component, string $permission, AbstractInstance|null $instance = null): bool;
    public function hasAdminPermission(string $component, string $permission, AbstractInstance|null $instance = null): bool;
}