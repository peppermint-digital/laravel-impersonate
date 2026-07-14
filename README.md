# peppermint/impersonate

Admin impersonation for Peppermint Laravel apps. A thin wrapper around
[`lab404/laravel-impersonate`](https://github.com/404labfr/laravel-impersonate)
that adds:

- **Spatie role gating** — only holders of the configured admin role can
  impersonate, and admins can never be impersonated (no admin→admin
  escalation).
- **Ready-made routes** — `impersonate.take` / `impersonate.leave`, gated to
  the admin role in the controller.
- **Frontend banner** — a helper that shares the current impersonation state
  plus a publishable Inertia/React banner component.

No migrations. The original admin id lives in the session only.

## Installation

Add the VCS repository and require the package:

```jsonc
// composer.json
"repositories": [
    { "type": "vcs", "url": "git@github.com:peppermint-digital/laravel-impersonate.git" }
]
```

```bash
composer require peppermint/impersonate
```

The service provider is auto-discovered. `lab404/laravel-impersonate` is pulled
in as a dependency and bootstraps itself.

## Setup

### 1. User model

Replace lab404's trait with ours on your authenticatable model:

```php
use Peppermint\Impersonate\Concerns\Impersonatable;

class User extends Authenticatable
{
    use Impersonatable;
}
```

### 2. Config — pick your admin gate

```bash
php artisan vendor:publish --tag=peppermint-impersonate-config
```

Who may impersonate is resolved in precedence `admin_method → admin_ability →
admin_role` (first configured wins), so the package works with or without
spatie/laravel-permission. Set the one that fits, leave the rest null:

```php
// config/peppermint-impersonate.php

// a) Spatie (or any hasRole(string) model):
'admin_role'   => 'admin',          // or 'administrator', 'super-admin', …

// b) a boolean method on the user model:
'admin_method' => 'isAdmin',        // or 'isAdministrator', …

// c) a Gate ability:
'admin_ability' => 'impersonate-users',

'user_model'       => null,          // defaults to the auth provider model
'take_redirect_to' => '/',
'leave_redirect_to'=> '/',
```

Values are plain strings (no closures) so config caching keeps working. Need
custom logic? Override `canImpersonate()` / `canBeImpersonated()` on your User
model — the class method wins over the trait.

### 3. Share state with the frontend

In `App\Http\Middleware\HandleInertiaRequests::share()`:

```php
use Peppermint\Impersonate\Support\Impersonation;

'impersonating' => Impersonation::state(),
```

### 4. Banner component (React or Vue)

```bash
# React (Inertia)
php artisan vendor:publish --tag=peppermint-impersonate-react
# Vue 3 (Inertia)
php artisan vendor:publish --tag=peppermint-impersonate-vue
```

Renders `resources/js/components/impersonation-banner.tsx` or
`ImpersonationBanner.vue`. Drop it high in your app layout:

```tsx
// React
import ImpersonationBanner from '@/components/impersonation-banner';
<ImpersonationBanner />
```

```vue
<!-- Vue -->
<script setup>import ImpersonationBanner from '@/components/ImpersonationBanner.vue';</script>
<template><ImpersonationBanner /></template>
```

### 5. Trigger it

Link admins to the take route from your user admin list:

```tsx
<Link href={`/impersonate/take/${user.id}`}>View as</Link>
```

## Routes

| Method | URI                       | Name                 |
|--------|---------------------------|----------------------|
| GET    | `impersonate/take/{id}`   | `impersonate.take`   |
| GET    | `impersonate/leave`       | `impersonate.leave`  |

Both are gated: `take` requires `canImpersonate()` (admin role) on the caller
and `canBeImpersonated()` on the target; `leave` requires an active
impersonation. Self-impersonation is rejected.

## API

Everything from lab404 stays available:

```php
$user->impersonate($other);
$user->isImpersonated();
$user->leaveImpersonation();

use Peppermint\Impersonate\Support\Impersonation;
Impersonation::state(); // array | null — for frontend sharing
```
