import DeleteConfirmation from '@/Components/DeleteConfirmation';
import VoteButton from '@/Components/VoteButton';
import { Ziggy } from '@/ziggy';
import { Link, router } from '@inertiajs/react';
import { useState } from 'react';
import { route } from 'ziggy-js';

export default function AspirasiCard({ item, initialVoted = false }) {
    const [voted, setVoted] = useState(initialVoted);
    const [votesCount, setVotesCount] = useState(item.votes_count ?? 0);

    const handleVote = (done) => {
        if (voted) return;

        const url = route('aspirasi.vote', { id: item.id }, Ziggy);

        router.post(
            url,
            {},
            {
                onSuccess: () => {
                    setVoted(true);
                    setVotesCount((prev) => prev + 1);
                    if (done) done();
                },
                onError: () => {
                    alert('Vote gagal. Coba lagi.');
                    if (done) done();
                },
            },
        );
    };

    return (
        <div className="mb-6 w-full max-w-2xl overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm transition hover:shadow-md">
            <div className="p-6">
                {/* Header */}
                <div className="flex items-start justify-between space-x-4">
                    <div className="min-w-0 flex-1">
                        <span className="mb-2 inline-block rounded bg-blue-50 px-3 py-1 text-xs font-medium text-blue-700">
                            {item.topik?.nama ?? 'Topik tidak ditemukan'}
                        </span>
                        <h3 className="line-clamp-2 text-xl font-semibold text-gray-900">
                            {item.judul}
                        </h3>
                    </div>
                    <span
                        className={`inline-flex items-center rounded px-3 py-1 text-xs font-medium ${
                            item.status === 'Selesai'
                                ? 'bg-green-400 text-white'
                                : item.status === 'Diterima' ||
                                    item.status === 'Diproses'
                                  ? 'bg-blue-100 text-blue-800'
                                  : item.status === 'Ditolak'
                                    ? 'bg-red-100 text-red-800'
                                    : 'bg-yellow-100 text-yellow-800'
                        }`}
                    >
                        {item.status}
                    </span>
                </div>

                {/* Isi */}
                <p className="mt-3 text-base leading-relaxed text-gray-700">
                    {item.isi}
                </p>
                {item.lampiran && (
                    <div className="mt-4 text-sm text-blue-700">
                        Lampiran:{' '}
                        <a
                            href={`/storage/${item.lampiran}`}
                            target="_blank"
                            rel="noopener noreferrer"
                            className="underline"
                        >
                            Lihat Lampiran
                        </a>
                    </div>
                )}

                {/* Footer */}
                <div className="mt-6 flex flex-col gap-4 border-t border-gray-100 pt-4 sm:flex-row sm:items-center sm:justify-between">
                    <div className="text-sm text-gray-600">
                        Pengirim:{' '}
                        <span className="font-medium text-gray-900">
                            {item.is_anonim
                                ? 'Anonim'
                                : item.pengirim || 'Tidak diketahui'}
                        </span>
                    </div>

                    <div className="flex items-center justify-end space-x-3">
                        <span className="flex items-center text-sm text-gray-600">
                            <svg
                                className="mr-1.5 h-4 w-4"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    strokeLinecap="round"
                                    strokeLinejoin="round"
                                    strokeWidth={2}
                                    d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"
                                />
                            </svg>
                            {votesCount}
                        </span>

                        <VoteButton initialVoted={voted} onVote={handleVote} />

                        {item.is_owner && (
                            <>
                                <Link
                                    href={route(
                                        'aspirasi.edit',
                                        { id: item.id },
                                        Ziggy,
                                    )}
                                    className="inline-flex items-center rounded-md border border-gray-300 bg-gray-50 px-3 py-1.5 text-xs text-gray-700 transition hover:bg-gray-100"
                                >
                                    <svg
                                        className="mr-1.5 h-4 w-4"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            strokeLinecap="round"
                                            strokeLinejoin="round"
                                            strokeWidth={2}
                                            d="M11 5H6a2 2 0 00-2 2v11.5A1.5 1.5 0 005.5 20H17a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11 17H8v-3l9.586-9.586z"
                                        />
                                    </svg>
                                    Update
                                </Link>

                                <DeleteConfirmation
                                    item={item}
                                    onDeleted={() => window.location.reload()}
                                />
                            </>
                        )}
                    </div>
                </div>

                {/* Tindak Lanjut */}
                {item.komentar_tindak_lanjut && (
                    <div className="mt-6 rounded-lg border border-blue-100 bg-blue-50 p-4">
                        <div className="flex space-x-3">
                            <div className="pt-0.5">
                                <svg
                                    className="h-5 w-5 text-blue-600"
                                    fill="currentColor"
                                    viewBox="0 0 20 20"
                                >
                                    <path
                                        fillRule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2h-1V9z"
                                        clipRule="evenodd"
                                    />
                                </svg>
                            </div>
                            <div className="flex-1">
                                <h4 className="text-sm font-medium text-blue-800">
                                    Tindak Lanjut Admin
                                </h4>
                                <div className="mt-2 space-y-1.5 text-sm text-blue-700">
                                    {item.komentar_tindak_lanjut.keterangan
                                        .split('\n')
                                        .map((line, index) => (
                                            <p key={index}>{line}</p>
                                        ))}
                                </div>
                                <p className="mt-2 text-xs text-blue-500">
                                    {new Date(
                                        item.komentar_tindak_lanjut.tanggal,
                                    ).toLocaleString('id-ID', {
                                        day: 'numeric',
                                        month: 'long',
                                        year: 'numeric',
                                        hour: '2-digit',
                                        minute: '2-digit',
                                    })}
                                </p>
                            </div>
                        </div>
                    </div>
                )}
            </div>
        </div>
    );
}
