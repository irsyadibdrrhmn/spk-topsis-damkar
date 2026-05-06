<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Hasil Perangkingan TOPSIS') }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('topsis.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                    Lihat Perhitungan
                </a>
                <button onclick="window.print()" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                    </svg>
                    Cetak
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            @if(session('warning'))
                <div class="px-4 py-3 rounded-lg bg-yellow-100 border border-yellow-400 text-yellow-700">
                    {{ session('warning') }}
                </div>
            @endif

            <!-- Period Filter -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center gap-4">
                        <label for="period_filter" class="text-sm font-medium text-gray-700 dark:text-gray-300">Periode Penilaian:</label>
                        <select id="period_filter" onchange="location.href='?period='+this.value" class="rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                            <option value="{{ date('Y-m') }}" {{ $period == date('Y-m') ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::parse(date('Y-m') . '-01')->format('F Y') }} (Bulan Ini)
                            </option>
                            @foreach($periods as $p)
                                @if($p != date('Y-m'))
                                <option value="{{ $p }}" {{ $period == $p ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::parse($p . '-01')->format('F Y') }}
                                </option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            @if(isset($topsisData))
            <!-- Top 3 Performers -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach(array_slice($topsisData['ranking'], 0, 3) as $index => $result)
                <div class="bg-gradient-to-br {{ $index == 0 ? 'from-yellow-400 to-yellow-500' : ($index == 1 ? 'from-gray-300 to-gray-400' : 'from-orange-400 to-orange-500') }} rounded-lg shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between mb-4">
                        <div class="text-6xl font-bold opacity-20">{{ $result['rank'] }}</div>
                        <div class="text-3xl">{{ $index == 0 ? '🥇' : ($index == 1 ? '🥈' : '🥉') }}</div>
                    </div>
                    <h3 class="text-xl font-bold mb-1">{{ $result['ppk']->name }}</h3>
                    <p class="text-sm opacity-90 mb-3">{{ $result['ppk']->jabatan }}</p>
                    <div class="bg-white bg-opacity-20 rounded-lg p-3">
                        <p class="text-xs opacity-75 mb-1">Nilai Preferensi</p>
                        <p class="text-2xl font-bold">{{ number_format($result['preference_value'], 4) }}</p>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Complete Ranking Table -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                        Perangkingan Lengkap - {{ \Carbon\Carbon::parse($period . '-01')->format('F Y') }}
                    </h3>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Peringkat</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nama PPK</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Jabatan</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">D+</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">D-</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nilai Preferensi</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Kategori</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @php
                                    $totalPPK = count($topsisData['ranking']);
                                @endphp
                                @foreach($topsisData['ranking'] as $result)
                                <tr class="{{ $result['rank'] <= 3 ? 'bg-green-50 dark:bg-green-900/10' : '' }}">
                                    <td class="px-6 py-4 text-center">
                                        <div class="inline-flex items-center justify-center w-10 h-10 rounded-full {{ $result['rank'] <= 3 ? 'bg-yellow-400 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300' }} font-bold">
                                            {{ $result['rank'] }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10 rounded-full bg-blue-500 flex items-center justify-center text-white font-bold">
                                                {{ strtoupper(substr($result['ppk']->name, 0, 2)) }}
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                    {{ $result['ppk']->name }}
                                                </div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                                    NIP: {{ $result['ppk']->nip }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $result['ppk']->jabatan }}
                                    </td>
                                    <td class="px-6 py-4 text-center text-sm font-mono text-gray-900 dark:text-gray-100">
                                        {{ number_format($result['positive_distance'], 4) }}
                                    </td>
                                    <td class="px-6 py-4 text-center text-sm font-mono text-gray-900 dark:text-gray-100">
                                        {{ number_format($result['negative_distance'], 4) }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="px-3 py-1 inline-flex text-sm font-mono font-bold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                            {{ number_format($result['preference_value'], 4) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if($result['rank'] <= 3)
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                Sangat Baik
                                            </span>
                                        @elseif($result['rank'] <= ceil($totalPPK / 2))
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                                Baik
                                            </span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                                Perlu Peningkatan
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Criteria Info -->
<div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
    <h4 class="text-sm font-semibold text-blue-900 dark:text-blue-100 mb-2">Kriteria Penilaian Kinerja PPK:</h4>
    <div class="space-y-2 text-sm text-blue-800 dark:text-blue-200">
        <div class="flex items-start">
            <span class="font-bold mr-2">C1 (50%):</span>
            <div>
                <strong>Kehadiran</strong> - Absensi, Apel pagi, Lembur
                <span class="ml-2 px-2 py-0.5 text-xs rounded bg-green-100 text-green-800">Benefit</span>
            </div>
        </div>
        <div class="flex items-start">
            <span class="font-bold mr-2">C2 (30%):</span>
            <div>
                <strong>Kinerja Pelayanan</strong> - Pencetakan Akte, KTP, KK
                <span class="ml-2 px-2 py-0.5 text-xs rounded bg-green-100 text-green-800">Benefit</span>
            </div>
        </div>
        <div class="flex items-start">
            <span class="font-bold mr-2">C3 (20%):</span>
            <div>
                <strong>Pelatihan</strong> - Jumlah pelatihan yang diikuti
                <span class="ml-2 px-2 py-0.5 text-xs rounded bg-green-100 text-green-800">Benefit</span>
            </div>
        </div>
    </div>
</div>

<style>
    @media print {
        .no-print,
        nav,
        header button,
        .flex.gap-2 {
            display: none !important;
        }
    }
</style>
@endif
</x-app-layout>
