import ApplicationLogo from '@/Components/ApplicationLogo';
import { Link } from '@inertiajs/react';

export default function GuestLayout({ children }) {
    return (
        <div className="flex min-h-screen flex-col items-center bg-gray-100 pt-6 sm:justify-center sm:pt-0">
            {/* Watermark ala website umum */}
            <div className="fixed bottom-4 left-1/2 z-50 -translate-x-1/2 text-sm text-gray-400 opacity-50">
                © 2025 AspirasiMahasiswa
            </div>

            <div>
                <Link href="/">
                    <ApplicationLogo className="h-20 w-auto fill-current text-gray-500" />
                </Link>
            </div>

            <div className="mt-6 w-full max-w-md px-4 sm:px-6 lg:px-8">
                <div className="overflow-hidden rounded-lg bg-white px-6 py-4 shadow-md">
                    {children}
                </div>
            </div>
        </div>
    );
}
