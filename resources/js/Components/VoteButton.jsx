import '@/../css/vote-button.css';
import clickSound from '@/utils/Audio/click.mp3';
import { useEffect, useState } from 'react';

export default function VoteButton({
    initialVoted = false,
    onVote,
    className = '',
}) {
    const [voted, setVoted] = useState(initialVoted);
    const [loading, setLoading] = useState(false);

    useEffect(() => {
        setVoted(initialVoted);
    }, [initialVoted]);

    const handleClick = () => {
        if (loading || voted) return;

        setLoading(true);

        // Mainkan suara dengan delay biar match dengan "Memproses..."
        setTimeout(() => {
            const audio = new Audio(clickSound);
            audio.play();
        }, 3300);

        if (onVote) {
            onVote(() => {
                setVoted(true);
                setLoading(false);
            });
        } else {
            // fallback kalau onVote tidak ada → simulasi selesai
            setTimeout(() => {
                setVoted(true);
                setLoading(false);
            }, 1000); // simulasi loading 1 detik
        }
    };

    return (
        <button
            type="button"
            onClick={handleClick}
            disabled={loading || voted}
            className={`click-scale flex items-center space-x-1 rounded-lg px-4 py-2 text-sm font-medium transition-all duration-200 focus:outline-none ${
                loading ? 'cursor-wait opacity-70' : ''
            } ${
                voted
                    ? 'cursor-not-allowed bg-gray-300 text-gray-700'
                    : 'bg-blue-600 text-white hover:bg-blue-700'
            } ${className}`}
        >
            {loading ? (
                <>
                    <svg
                        className="h-4 w-4 animate-spin text-white"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                    >
                        <circle
                            className="opacity-25"
                            cx="12"
                            cy="12"
                            r="10"
                            stroke="currentColor"
                            strokeWidth="4"
                        ></circle>
                        <path
                            className="opacity-75"
                            fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                        ></path>
                    </svg>
                    <span>Memproses...</span>
                </>
            ) : (
                <>
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 16 16"
                        fill="currentColor"
                        className="size-4"
                    >
                        <path d="M7.557 2.066A.75.75 0 0 1 8 2.75v10.5a.75.75 0 0 1-1.248.56L3.59 11H2a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1h1.59l3.162-2.81a.75.75 0 0 1 .805-.124ZM12.95 3.05a.75.75 0 1 0-1.06 1.06 5.5 5.5 0 0 1 0 7.78.75.75 0 1 0 1.06 1.06 7 7 0 0 0 0-9.9Z" />
                        <path d="M10.828 5.172a.75.75 0 1 0-1.06 1.06 2.5 2.5 0 0 1 0 3.536.75.75 0 1 0 1.06 1.06 4 4 0 0 0 0-5.656Z" />
                    </svg>
                    <span>{voted ? 'Voted' : 'Vote'}</span>
                </>
            )}
        </button>
    );
}
