<?php

namespace Peppermint\Impersonate\Concerns;

use Lab404\Impersonate\Models\Impersonate as Lab404Impersonate;

/**
 * Drop-in replacement for lab404's Impersonate trait.
 *
 * It keeps all of lab404's behaviour (impersonate / leaveImpersonation /
 * isImpersonated …) but overrides the two authorization gates so that:
 *
 *  - only users with the configured admin role may impersonate, and
 *  - users with the admin role can never be impersonated (no admin→admin
 *    privilege escalation).
 *
 * Add it to your authenticatable model instead of lab404's trait:
 *
 *     use Peppermint\Impersonate\Concerns\Impersonatable;
 *
 *     class User extends Authenticatable
 *     {
 *         use Impersonatable;
 *     }
 */
trait Impersonatable
{
    use Lab404Impersonate {
        canImpersonate as protected lab404CanImpersonate;
        canBeImpersonated as protected lab404CanBeImpersonated;
    }

    /**
     * Only holders of the configured admin role may impersonate others.
     */
    public function canImpersonate(): bool
    {
        return $this->hasImpersonateAdminRole();
    }

    /**
     * Admins can never be impersonated — prevents admin→admin escalation.
     */
    public function canBeImpersonated(): bool
    {
        return ! $this->hasImpersonateAdminRole();
    }

    protected function hasImpersonateAdminRole(): bool
    {
        $role = config('peppermint-impersonate.admin_role', 'admin');

        return method_exists($this, 'hasRole') && $this->hasRole($role);
    }
}
