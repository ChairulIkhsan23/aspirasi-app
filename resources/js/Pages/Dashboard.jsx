import AspirasiCard from '@/Components/AspirasiCard';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link, router, useForm } from '@inertiajs/react';
import { useState } from 'react';

export default function Dashboard({ aspirasis, votedAspirasiIds, auth }) {
    const { post } = useForm();
    const [dataAspirasi, setDataAspirasi] = useState(() =>
        Array.isArray(aspirasis) ? aspirasis : [],
    );
    const [votedIds, setVotedIds] = useState(() =>
        Array.isArray(votedAspirasiIds) ? votedAspirasiIds : [],
    );

    const handleVote = (id, done) => {
        if (votedIds.includes(id)) {
            done?.(); // jika sudah vote, hentikan loading button
            return;
        }

        // Optimistic update
        setDataAspirasi((prev) =>
            prev.map((item) =>
                item.id === id
                    ? { ...item, votes_count: (item.votes_count || 0) + 1 }
                    : item,
            ),
        );
        setVotedIds((prev) => [...prev, id]);

        post(route('aspirasi.vote', id), {
            preserveScroll: true,
            onSuccess: () => {
                // Refresh aspirasis & votedAspirasiIds fresh dari server
                router.reload({
                    only: ['aspirasis', 'votedAspirasiIds'],
                    onFinish: done, // selesai loading button
                });
            },
            onError: () => {
                // Rollback jika error
                setDataAspirasi((prev) =>
                    prev.map((item) =>
                        item.id === id
                            ? {
                                  ...item,
                                  votes_count: (item.votes_count || 1) - 1,
                              }
                            : item,
                    ),
                );
                setVotedIds((prev) => prev.filter((vId) => vId !== id));
                done?.();
            },
        });
    };

    return (
        <AuthenticatedLayout
            user={auth.user}
            header={
                <div className="flex items-center justify-between">
                    <h2 className="text-xl font-semibold leading-tight text-gray-800">
                        Dashboard
                    </h2>
                    <Link
                        href={route('aspirasi.create')}
                        className="inline-flex items-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white transition duration-150 ease-in-out hover:bg-indigo-700 focus:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 active:bg-indigo-900"
                    >
                        Tambah Aspirasi
                    </Link>
                </div>
            }
        >
            <Head title="Dashboard" />

            <div className="py-12">
                <div className="mx-auto max-w-7xl space-y-4 sm:px-6 lg:px-8">
                    {dataAspirasi.length > 0 ? (
                        dataAspirasi.map((item) => (
                            <AspirasiCard
                                key={item.id}
                                item={item}
                                initialVoted={votedIds.includes(item.id)}
                                onVote={(done) => handleVote(item.id, done)}
                            />
                        ))
                    ) : (
                        <div className="overflow-hidden bg-white p-6 text-center shadow-sm sm:rounded-lg">
                            <p className="mb-4 text-gray-600">
                                Belum ada aspirasi yang masuk.
                            </p>
                            <Link
                                href={route('aspirasi.create')}
                                className="inline-flex items-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white transition duration-150 ease-in-out hover:bg-indigo-700 focus:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 active:bg-indigo-900"
                            >
                                Tambah Aspirasi Pertama
                            </Link>
                        </div>
                    )}
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
