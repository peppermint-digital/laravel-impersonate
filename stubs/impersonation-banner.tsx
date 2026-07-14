import { Link, usePage } from '@inertiajs/react';

type ImpersonationState = {
    active: boolean;
    impersonator: { id: number | string; name: string | null; email: string | null } | null;
    user: { id: number | string; name: string | null; email: string | null };
    leave_url: string;
};

/**
 * Persistent banner shown while an admin is impersonating another user.
 *
 * Reads the `impersonating` prop shared by the backend (see
 * Peppermint\Impersonate\Support\Impersonation::state()). Renders nothing when
 * no impersonation is active. Place it once, high in your app layout.
 */
export function ImpersonationBanner() {
    const impersonating = usePage().props.impersonating as ImpersonationState | null | undefined;

    if (!impersonating?.active) {
        return null;
    }

    const target = impersonating.user.name || impersonating.user.email || `#${impersonating.user.id}`;
    const admin = impersonating.impersonator?.name || impersonating.impersonator?.email;

    return (
        <div className="sticky top-0 z-50 flex items-center justify-center gap-3 bg-amber-500 px-4 py-2 text-sm font-medium text-amber-950 shadow-sm">
            <span aria-hidden className="text-base leading-none">👁️</span>
            <span>
                Viewing as <strong>{target}</strong>
                {admin ? <span className="opacity-70"> — signed in as {admin}</span> : null}
            </span>
            <Link
                href={impersonating.leave_url}
                className="ml-2 rounded-md bg-amber-950/10 px-2.5 py-1 font-semibold underline-offset-2 hover:bg-amber-950/20 hover:underline"
            >
                Leave
            </Link>
        </div>
    );
}

export default ImpersonationBanner;
