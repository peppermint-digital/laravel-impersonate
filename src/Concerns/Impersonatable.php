<?php

namespace Peppermint\Impersonate\Concerns;

use Lab404\Impersonate\Models\Impersonate as Lab404Impersonate;
use Peppermint\Impersonate\Support\AdminGate;

/**
 * Drop-in replacement for lab404's Impersonate trait.
 *
 * It keeps all of lab404's behaviour (impersonate / leaveImpersonation /
 * isImpersonated …) but overrides the two authorization gates so that:
 *
 *  - only users the configured admin gate accepts may impersonate, and
 *  - admins can never be impersonated (no admin→admin privilege escalation).
 *
 * The admin gate is resolved via config (admin_method / admin_ability /
 * admin_role) so this works with or without spatie/laravel-permission — see
 * Peppermint\Impersonate\Support\AdminGate.
 *
 * Add it to your authenticatable model instead of lab404's trait:
 *
 *     use Peppermint\Impersonate\Concerns\Impersonatable;
 *
 *     class User extends Authenticatable
 *     {
 *         use Impersonatable;
 *     }
 *
 * Need bespoke rules? Just override canImpersonate()/canBeImpersonated() on
 * your model — the class method wins over this trait.
 */
trait Impersonatable
{
    use Lab404Impersonate {
        canImpersonate as protected lab404CanImpersonate;
        canBeImpersonated as protected lab404CanBeImpersonated;
    }

    /**
     * Only admins (per the configured gate) may impersonate others.
     */
    public function canImpersonate(): bool
    {
        return AdminGate::isAdmin($this);
    }

    /**
     * Admins can never be impersonated — prevents admin→admin escalation.
     */
    public function canBeImpersonated(): bool
    {
        return ! AdminGate::isAdmin($this);
    }
}
