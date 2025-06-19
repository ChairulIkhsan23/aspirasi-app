import AspirasiCard from '@/Components/AspirasiCard';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, router, useForm } from '@inertiajs/react';
import { useState } from 'react';

export default function Dashboard({ aspirasis, votedAspirasiIds }) {
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
            header={
                <h2 className="text-xl font-semibold leading-tight text-gray-800">
                    Dashboard
                </h2>
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
                        <p className="text-gray-600">
                            Belum ada aspirasi yang masuk.
                        </p>
                    )}
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
