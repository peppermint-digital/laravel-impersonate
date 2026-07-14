<?php

namespace Peppermint\Impersonate\Support;

use Illuminate\Support\Facades\Gate;

/**
 * Resolves whether a given user counts as an "admin" for impersonation,
 * decoupled from any specific role system.
 *
 * Precedence (first configured wins): admin_method → admin_ability → admin_role.
 * See config/peppermint-impersonate.php.
 */
class AdminGate
{
    public static function isAdmin(object $user): bool
    {
        $method = config('peppermint-impersonate.admin_method');
        if (! empty($method) && method_exists($user, $method)) {
            return (bool) $user->{$method}();
        }

        $ability = config('peppermint-impersonate.admin_ability');
        if (! empty($ability)) {
            return Gate::forUser($user)->allows($ability);
        }

        $role = config('peppermint-impersonate.admin_role');
        if (! empty($role) && method_exists($user, 'hasRole')) {
            return (bool) $user->hasRole($role);
        }

        return false;
    }
}
