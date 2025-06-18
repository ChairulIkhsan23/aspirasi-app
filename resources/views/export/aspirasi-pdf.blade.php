<!-- HALAMAN LAPORAN ASPIRASI PROFESIONAL -->
<div style="font-family: 'Arial', sans-serif; max-width: 800px; margin: 0 auto; padding: 20px;">

    <!-- Header Laporan -->
    <div style="text-align: center; margin-bottom: 30px;">
        <h1 style="font-size: 18px; font-weight: bold; margin-bottom: 5px;">LAPORAN ASPIRASI MAHASISWA</h1>
        <div style="font-size: 14px; color: #555;">
            Periode: {{ \Carbon\Carbon::now()->locale('id')->isoFormat('MMMM YYYY') }}
        </div>
    </div>

    <!-- Informasi Ringkasan -->
    <div style="background-color: #f5f5f5; padding: 15px; border-radius: 5px; margin-bottom: 25px;">
        <h2 style="font-size: 15px; font-weight: bold; margin-bottom: 10px;">RINGKASAN</h2>
        <table width="100%" style="font-size: 12px;">
            <tr>
                <td width="25%">Total Aspirasi</td>
                <td width="5%">:</td>
                <td width="70%">{{ count($aspirasis) }} data</td>
            </tr>
            <tr>
                <td>Periode</td>
                <td>:</td>
                <td>
                    @if(request('bulan') || request('tahun') || request('tanggal'))
                        {{ request('bulan') ? \Carbon\Carbon::create()->month(request('bulan'))->monthName : '' }}
                        {{ request('tahun') ? request('tahun') : '' }}
                        {{ request('tanggal') ? \Carbon\Carbon::parse(request('tanggal'))->format('d F Y') : '' }}
                    @else
                        Semua Data
                    @endif
                </td>
            </tr>
            <tr>
                <td>Tanggal Cetak</td>
                <td>:</td>
                <td>{{ \Carbon\Carbon::now()->tz('Asia/Jakarta')->locale('id')->isoFormat('DD MMMM YYYY HH:mm') }}</td>
            </tr>
        </table>
    </div>

    <!-- Tabel Data -->
    <table border="1" cellpadding="8" cellspacing="0" width="100%" style="font-size: 11px; border-collapse: collapse;">
        <thead>
            <tr style="background-color: #eaeaea;">
                <th width="5%" style="text-align: center;">No</th>
                <th width="15%">Judul</th>
                <th width="35%">Isi Aspirasi</th>
                <th width="10%" style="text-align: center;">Status</th>
                <th width="15%" style="text-align: center;">Tanggal</th>
                <th width="20%" style="text-align: center;">Lampiran</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($aspirasis as $index => $aspirasi)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td>{{ $aspirasi->judul }}</td>
                    <td>{{ $aspirasi->isi }}</td>
                    <td style="text-align: center;">
                    <span style="display: inline-block; padding: 3px 8px; border-radius: 3px;
                                @switch($aspirasi->status)
                                    @case('Selesai')
                                        background-color: #d4edda; color: #155724;
                                        @break
                                    @case('Diproses')
                                        background-color: #fff3cd; color: #856404;
                                        @break
                                    @case('Ditolak')
                                        background-color: #f8d7da; color: #721c24;
                                        @break
                                    @default
                                        background-color: #e2e3e5; color: #383d41;
                                @endswitch">
                        {{ $aspirasi->status }}
                    </span>
                    </td>
                    <td style="text-align: center;">{{ $aspirasi->created_at->format('d/m/Y') }}</td>
                    <td style="text-align: center;">
                        @if ($aspirasi->lampiran)
                            <img src="{{ public_path('storage/' . $aspirasi->lampiran) }}" 
                                 alt="Lampiran" 
                                 style="max-width: 100px; max-height: 60px; display: block; margin: 0 auto;">
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Footer -->
    <div style="margin-top: 30px; font-size: 11px; color: #777; text-align: right;">
        <div style="border-top: 1px solid #eee; padding-top: 10px;">
            Dicetak oleh: Sistem Informasi Aspirasi Mahasiswa<br>
            {{ \Carbon\Carbon::now()->locale('id')->isoFormat('DD MMMM YYYY HH:mm:ss') }}
        </div>
    </div>
</div>