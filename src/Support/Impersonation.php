<?php

namespace Peppermint\Impersonate\Support;

use Lab404\Impersonate\Services\ImpersonateManager;

class Impersonation
{
    /**
     * Returns the current impersonation state for sharing with the frontend,
     * or null when no impersonation is active.
     *
     * @return array{active: bool, impersonator: array{id: int|string, name: string|null, email: string|null}|null, user: array{id: int|string, name: string|null, email: string|null}, leave_url: string}|null
     */
    public static function state(): ?array
    {
        $user = auth()->user();

        if ($user === null || ! method_exists($user, 'isImpersonated') || ! $user->isImpersonated()) {
            return null;
        }

        return [
            'active' => true,
            'impersonator' => self::person(self::impersonator()),
            'user' => self::person($user),
            'leave_url' => route('impersonate.leave'),
        ];
    }

    protected static function impersonator(): ?object
    {
        $manager = app(ImpersonateManager::class);
        $id = $manager->getImpersonatorId();

        if ($id === null) {
            return null;
        }

        $model = config('peppermint-impersonate.user_model')
            ?? config('auth.providers.users.model');

        return $model::query()->find($id);
    }

    /**
     * @return array{id: int|string, name: string|null, email: string|null}|null
     */
    protected static function person(?object $user): ?array
    {
        if ($user === null) {
            return null;
        }

        return [
            'id' => $user->getAuthIdentifier(),
            'name' => $user->name ?? null,
            'email' => $user->email ?? null,
        ];
    }
}
