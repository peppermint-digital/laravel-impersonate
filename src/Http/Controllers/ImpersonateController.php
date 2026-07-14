<?php

namespace Peppermint\Impersonate\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ImpersonateController
{
    /**
     * Start impersonating the given user.
     */
    public function take(Request $request, int|string $id): RedirectResponse
    {
        $admin = $request->user();
        $target = $this->userModel()::query()->findOrFail($id);

        abort_unless($admin !== null && $this->canImpersonate($admin), 403);
        abort_unless($this->canBeImpersonated($target), 403);
        abort_if($admin->getAuthIdentifier() === $target->getAuthIdentifier(), 403, 'You cannot impersonate yourself.');

        $admin->impersonate($target);

        return redirect(config('peppermint-impersonate.take_redirect_to', '/'));
    }

    /**
     * Stop impersonating and return to the original admin account.
     */
    public function leave(Request $request): RedirectResponse
    {
        $user = $request->user();

        abort_unless($user !== null && method_exists($user, 'isImpersonated') && $user->isImpersonated(), 403);

        $user->leaveImpersonation();

        return redirect(config('peppermint-impersonate.leave_redirect_to', '/'));
    }

    /**
     * @return class-string<\Illuminate\Database\Eloquent\Model>
     */
    protected function userModel(): string
    {
        return config('peppermint-impersonate.user_model')
            ?? config('auth.providers.users.model');
    }

    protected function canImpersonate(object $user): bool
    {
        return method_exists($user, 'canImpersonate') && $user->canImpersonate();
    }

    protected function canBeImpersonated(object $user): bool
    {
        return method_exists($user, 'canBeImpersonated') && $user->canBeImpersonated();
    }
}
