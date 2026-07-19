<script setup lang="ts">
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';

type ImpersonationState = {
    active: boolean;
    impersonator: { id: number | string; name: string | null; email: string | null } | null;
    user: { id: number | string; name: string | null; email: string | null };
    leave_url: string;
};

/**
 * Persistent banner shown while an admin is impersonating another user.
 *
 * Reads the `impersonating` shared prop (Peppermint\Impersonate\Support\
 * Impersonation::state()). Renders nothing when no impersonation is active.
 * Place it once, high in your app layout.
 */
const page = usePage();
const state = computed(() => page.props.impersonating as ImpersonationState | null | undefined);
const target = computed(() => {
    const u = state.value?.user;
    return u ? (u.name || u.email || `#${u.id}`) : '';
});
const admin = computed(() => state.value?.impersonator?.name || state.value?.impersonator?.email || '');
</script>

<template>
    <div
        v-if="state?.active"
        class="sticky top-0 z-50 flex flex-wrap items-center justify-center gap-x-3 gap-y-1 bg-amber-500 px-4 py-2 text-center text-sm font-medium text-amber-950 shadow-sm dark:bg-amber-400"
    >
        <span aria-hidden class="text-base leading-none">👁️</span>
        <span>
            Viewing as <strong>{{ target }}</strong>
            <span v-if="admin" class="opacity-70"> — signed in as {{ admin }}</span>
        </span>
        <!-- Native <a> (full page load), NOT an Inertia <Link>: leaving
             impersonation switches the session identity back to the admin.
             An SPA visit would keep Inertia's prefetch/history cache built as
             the impersonated user, so a stale page (wrong identity, no banner)
             could be shown until a hard refresh. A full load resets that cache. -->
        <a
            :href="state.leave_url"
            class="ml-1 rounded-md bg-amber-950/10 px-2.5 py-1 font-semibold underline-offset-2 hover:bg-amber-950/20 hover:underline"
        >
            Leave
        </a>
    </div>
</template>
