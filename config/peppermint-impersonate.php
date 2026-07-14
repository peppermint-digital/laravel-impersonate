<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Who may impersonate — the admin gate
    |--------------------------------------------------------------------------
    |
    | A user may impersonate others when they are considered an "admin", and a
    | user can never be impersonated when they are an admin (prevents
    | admin→admin privilege escalation).
    |
    | The admin check is resolved in this precedence (first match wins), so the
    | package works whether or not the app uses spatie/laravel-permission:
    |
    |   1. admin_method  — a no-arg boolean method on the user model,
    |                       e.g. 'isAdmin' / 'isAdministrator'
    |   2. admin_ability — a Gate ability checked via Gate::forUser($user)
    |   3. admin_role    — a role name checked via $user->hasRole($role)
    |                       (Spatie, or any model exposing hasRole(string))
    |
    | Set exactly the one that fits your app; leave the others null. Values must
    | be plain strings (no closures) so config caching keeps working.
    |
    */
    'admin_method' => env('IMPERSONATE_ADMIN_METHOD'),
    'admin_ability' => env('IMPERSONATE_ADMIN_ABILITY'),
    'admin_role' => env('IMPERSONATE_ADMIN_ROLE', 'admin'),

    /*
    |--------------------------------------------------------------------------
    | User model
    |--------------------------------------------------------------------------
    |
    | The Eloquent model used to resolve impersonation targets. When null it
    | falls back to the model configured for the default auth provider.
    |
    */
    'user_model' => null,

    /*
    |--------------------------------------------------------------------------
    | Redirects
    |--------------------------------------------------------------------------
    */
    'take_redirect_to' => '/',
    'leave_redirect_to' => '/',

    /*
    |--------------------------------------------------------------------------
    | Route registration
    |--------------------------------------------------------------------------
    */
    'register_routes' => true,
    'route_middleware' => ['web', 'auth'],

];
