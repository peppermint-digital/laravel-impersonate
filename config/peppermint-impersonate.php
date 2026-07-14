<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Admin role
    |--------------------------------------------------------------------------
    |
    | The Spatie role a user must have to be allowed to impersonate others.
    | Users with this role can start impersonation; users with this role can
    | NOT be impersonated (prevents admin-to-admin privilege escalation).
    |
    */
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
    |
    | Where to send the browser after starting / leaving impersonation.
    |
    */
    'take_redirect_to' => '/',
    'leave_redirect_to' => '/',

    /*
    |--------------------------------------------------------------------------
    | Route registration
    |--------------------------------------------------------------------------
    |
    | Toggle the package's built-in web routes (impersonate.take / .leave) and
    | the middleware applied to them. The routes are always gated to the admin
    | role inside the controller regardless of this middleware list.
    |
    */
    'register_routes' => true,
    'route_middleware' => ['web', 'auth'],

];
