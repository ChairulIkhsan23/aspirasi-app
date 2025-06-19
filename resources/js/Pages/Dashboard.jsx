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
            done?.(); // Jika sudah vote, langsung stop loading
            return;
        }

        // Optimistic UI update
        setDataAspirasi((prev) =>
            prev.map((item) =>
                item.id === id
                    ? { ...item, votes_count: item.votes_count + 1 }
                    : item,
            ),
        );
        setVotedIds((prev) => [...prev, id]);

        post(route('aspirasi.vote', id), {
            preserveScroll: true,
            onSuccess: () => {
                // Setelah sukses vote, reload data fresh
                router.reload({
                    only: ['aspirasis', 'votedAspirasiIds'],
                    onFinish: done, // agar loading selesai setelah reload selesai
                });
            },
            onError: () => {
                // Rollback jika gagal vote
                setDataAspirasi((prev) =>
                    prev.map((item) =>
                        item.id === id
                            ? { ...item, votes_count: item.votes_count - 1 }
                            : item,
                    ),
                );
                setVotedIds((prev) => prev.filter((vId) => vId !== id));
                done?.(); // Stop loading walau error
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
                                onVote={handleVote}
                                voted={votedIds.includes(item.id)}
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
