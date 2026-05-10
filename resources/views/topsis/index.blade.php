<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Perhitungan TOPSIS') }}
            </h2>
            <a href="{{ route('topsis.ranking') }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring focus:ring-green-300 disabled:opacity-25 transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                Lihat Ranking
            </a>
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
                        <label for="period_filter" class="text-sm font-medium text-gray-700 dark:text-gray-300">Filter Periode:</label>
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

            @if(isset($matrix) && isset($topsisData))
            <!-- Step 1: Decision Matrix -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <div class="flex-shrink-0 w-10 h-10 rounded-full bg-blue-500 flex items-center justify-center text-white font-bold">
                            1
                        </div>
                        <h3 class="ml-3 text-lg font-semibold text-gray-900 dark:text-gray-100">Matriks Keputusan (Decision Matrix)</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Alternatif</th>
                                    @foreach($criteria as $c)
                                        <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">{{ $c->code }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($personil as $p)
                                <tr>
                                    <td class="px-4 py-2 text-sm font-medium text-gray-900 dark:text-gray-100">{{ $personil->name }}</td>
                                    @foreach($criteria as $c)
                                        <td class="px-4 py-2 text-center text-sm text-gray-900 dark:text-gray-100">
                                            {{ number_format($matrix[$personil->id][$c->id], 2) }}
                                        </td>
                                    @endforeach
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Step 2: Normalized Matrix -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <div class="flex-shrink-0 w-10 h-10 rounded-full bg-green-500 flex items-center justify-center text-white font-bold">
                            2
                        </div>
                        <h3 class="ml-3 text-lg font-semibold text-gray-900 dark:text-gray-100">Matriks Ternormalisasi (Normalized Matrix)</h3>
                    </div>
                    <div class="mb-4 p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg text-sm text-gray-700 dark:text-gray-300">
                        <strong>Rumus:</strong> r<sub>ij</sub> = x<sub>ij</sub> / √(Σx<sub>ij</sub>²)
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Alternatif</th>
                                    @foreach($criteria as $c)
                                        <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">{{ $c->code }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($personil as $p)
                                <tr>
                                    <td class="px-4 py-2 text-sm font-medium text-gray-900 dark:text-gray-100">{{ $personil->name }}</td>
                                    @foreach($criteria as $c)
                                        <td class="px-4 py-2 text-center text-sm text-gray-900 dark:text-gray-100 font-mono">
                                            {{ number_format($topsisData['normalized'][$personil->id][$c->id], 4) }}
                                        </td>
                                    @endforeach
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Step 3: Weighted Normalized Matrix -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <div class="flex-shrink-0 w-10 h-10 rounded-full bg-yellow-500 flex items-center justify-center text-white font-bold">
                            3
                        </div>
                        <h3 class="ml-3 text-lg font-semibold text-gray-900 dark:text-gray-100">Matriks Ternormalisasi Terbobot</h3>
                    </div>
                    <div class="mb-4 p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg text-sm text-gray-700 dark:text-gray-300">
                        <strong>Rumus:</strong> y<sub>ij</sub> = w<sub>j</sub> × r<sub>ij</sub>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Alternatif</th>
                                    @foreach($criteria as $c)
                                        <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                            {{ $c->code }}<br>
                                            <span class="text-xs font-normal">(w={{ $c->weight }})</span>
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($personil as $p)
                                <tr>
                                    <td class="px-4 py-2 text-sm font-medium text-gray-900 dark:text-gray-100">{{ $personil->name }}</td>
                                    @foreach($criteria as $c)
                                        <td class="px-4 py-2 text-center text-sm text-gray-900 dark:text-gray-100 font-mono">
                                            {{ number_format($topsisData['weighted'][$personil->id][$c->id], 4) }}
                                        </td>
                                    @endforeach
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Step 4: Ideal Solutions -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <div class="flex-shrink-0 w-10 h-10 rounded-full bg-purple-500 flex items-center justify-center text-white font-bold">
                            4
                        </div>
                        <h3 class="ml-3 text-lg font-semibold text-gray-900 dark:text-gray-100">Solusi Ideal Positif (A+) dan Negatif (A-)</h3>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- A+ -->
                        <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-4">
                            <h4 class="text-md font-semibold text-green-900 dark:text-green-100 mb-3">A+ (Solusi Ideal Positif)</h4>
                            <div class="space-y-2">
                                @foreach($criteria as $c)
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-700 dark:text-gray-300">{{ $c->code }}:</span>
                                    <span class="text-sm font-mono font-semibold text-gray-900 dark:text-gray-100">
                                        {{ number_format($topsisData['idealPositive'][$c->id], 4) }}
                                    </span>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- A- -->
                        <div class="bg-red-50 dark:bg-red-900/20 rounded-lg p-4">
                            <h4 class="text-md font-semibold text-red-900 dark:text-red-100 mb-3">A- (Solusi Ideal Negatif)</h4>
                            <div class="space-y-2">
                                @foreach($criteria as $c)
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-700 dark:text-gray-300">{{ $c->code }}:</span>
                                    <span class="text-sm font-mono font-semibold text-gray-900 dark:text-gray-100">
                                        {{ number_format($topsisData['idealNegative'][$c->id], 4) }}
                                    </span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 5: Separation Measures -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <div class="flex-shrink-0 w-10 h-10 rounded-full bg-red-500 flex items-center justify-center text-white font-bold">
                            5
                        </div>
                        <h3 class="ml-3 text-lg font-semibold text-gray-900 dark:text-gray-100">Jarak Terhadap Solusi Ideal</h3>
                    </div>
                    <div class="mb-4 p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg text-sm text-gray-700 dark:text-gray-300">
                        <strong>D+:</strong> √(Σ(y<sub>ij</sub> - y<sub>j</sub>+)²) | 
                        <strong>D-:</strong> √(Σ(y<sub>ij</sub> - y<sub>j</sub>-)²)
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Alternatif</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">D+ (Jarak ke A+)</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">D- (Jarak ke A-)</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($personil as $p)
                                <tr>
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-gray-100">{{ $personil->name }}</td>
                                    <td class="px-6 py-4 text-center text-sm font-mono text-gray-900 dark:text-gray-100">
                                        {{ number_format($topsisData['separationPositive'][$personil->id], 6) }}
                                    </td>
                                    <td class="px-6 py-4 text-center text-sm font-mono text-gray-900 dark:text-gray-100">
                                        {{ number_format($topsisData['separationNegative'][$personil->id], 6) }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Step 6: Preference Values -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <div class="flex-shrink-0 w-10 h-10 rounded-full bg-indigo-500 flex items-center justify-center text-white font-bold">
                            6
                        </div>
                        <h3 class="ml-3 text-lg font-semibold text-gray-900 dark:text-gray-100">Nilai Preferensi (Preference Value)</h3>
                    </div>
                    <div class="mb-4 p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg text-sm text-gray-700 dark:text-gray-300">
                        <strong>Rumus:</strong> V<sub>i</sub> = D- / (D+ + D-)
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Alternatif</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Nilai Preferensi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($topsisData['ranking'] as $result)
                                <tr>
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-gray-100">
                                        {{ $result['ppk']->name }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="px-3 py-1 inline-flex text-sm font-mono font-semibold rounded-full bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200">
                                            {{ number_format($result['preference_value'], 6) }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- CTA -->
            <div class="bg-gradient-to-r from-green-500 to-emerald-600 rounded-lg shadow-lg p-6 text-center">
                <h3 class="text-2xl font-bold text-white mb-2">Perhitungan TOPSIS Selesai!</h3>
                <p class="text-green-100 mb-6">Lihat hasil perangkingan lengkap untuk menentukan tenaga PPK terbaik</p>
                <a href="{{ route('topsis.ranking', ['period' => $period]) }}" class="inline-flex items-center px-6 py-3 bg-white text-green-600 rounded-lg font-semibold hover:bg-green-50 transition shadow-lg">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    Lihat Hasil Ranking
                </a>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>