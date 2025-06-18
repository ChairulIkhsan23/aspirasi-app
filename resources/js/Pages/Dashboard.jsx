import AspirasiCard from '@/Components/AspirasiCard';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, useForm } from '@inertiajs/react';
import { useState } from 'react';

export default function Dashboard({ aspirasis, votedAspirasiIds }) {
    const { post } = useForm();
    const [dataAspirasi, setDataAspirasi] = useState(aspirasis);
    const [votedIds, setVotedIds] = useState(votedAspirasiIds);

    const handleVote = (id) => {
        if (votedIds.includes(id)) return;

        // Optimistic update
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
            onError: () => {
                // Rollback jika gagal
                setDataAspirasi((prev) =>
                    prev.map((item) =>
                        item.id === id
                            ? { ...item, votes_count: item.votes_count - 1 }
                            : item,
                    ),
                );
                setVotedIds((prev) => prev.filter((vId) => vId !== id));
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
                <div className="mx-auto max-w-7xl sm:px-6 lg:px-8">
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
