<div>
    @if ($record && $record->lampiran)
        <p>
            <a href="{{ asset('storage/' . $record->lampiran) }}"
            target="_blank"
            class="text-blue-600 underline inline-flex items-center gap-1">
                Download Lampiran
                <svg xmlns="http://www.w3.org/2000/svg"
                    class="h-8 w-8"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1M12 12v6m0 0l-3-3m3 3l3-3m0-6h.01" />
                </svg>
            </a>
        </p>

        @if (Str::endsWith($record->lampiran, ['.jpg', '.jpeg', '.png', '.gif']))
            <img src="{{ asset('storage/' . $record->lampiran) }}"
                 alt="Lampiran"
                 class="mt-2 rounded w-72 shadow" />
        @elseif (Str::endsWith($record->lampiran, ['.pdf']))
            <iframe src="{{ asset('storage/' . $record->lampiran) }}"
                    class="w-full h-64 mt-2 border rounded"></iframe>
        @else
            <p class="mt-2 italic text-gray-500">File tidak dapat dipreview.</p>
        @endif
    @else
        <p class="italic text-gray-500">Tidak ada lampiran.</p>
    @endif
</div>
