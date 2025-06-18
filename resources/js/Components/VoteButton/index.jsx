export default function VoteButton({ onClick, voted }) {
    return (
        <button
            onClick={onClick}
            disabled={voted}
            className={`rounded px-4 py-1 text-sm text-white transition ${
                voted
                    ? 'cursor-not-allowed bg-gray-400'
                    : 'bg-blue-600 hover:bg-blue-700'
            }`}
        >
            {voted ? 'Sudah Vote' : 'Vote'}
        </button>
    );
}
