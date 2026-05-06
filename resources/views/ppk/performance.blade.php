<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Kinerja Saya') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Profile Section -->
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 mb-6 text-white">
                <div class="flex items-center">
                    <div class="flex-shrink-0 h-20 w-20 rounded-full bg-white bg-opacity-20 flex items-center justify-center text-3xl font-bold">
                        {{ strtoupper(substr($user->name, 0, 2)) }}
                    </div>
                    <div class="ml-6">
                        <h3 class="text-2xl font-bold">{{ $user->name }}</h3>
                        <p class="text-blue-100">{{ $user->jabatan }}</p>
                        <p class="text-sm text-blue-200 mt-1">NIP: {{ $user->nip }}</p>
                    </div>
                </div>
            </div>

            <!-- Performance History -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Riwayat Penilaian Kinerja</h3>
                    
                    @if($results->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Periode</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Peringkat</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">D+ (Jarak Positif)</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">D- (Jarak Negatif)</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nilai Preferensi</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Kategori</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @php
                                        $totalPPK = \App\Models\User::where('role', 'ppk')->count();
                                    @endphp
                                    @foreach($results as $result)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            {{ \Carbon\Carbon::parse($result->period . '-01')->format('F Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900 dark:text-gray-100">
                                            {{ $result->rank }} / {{ $totalPPK }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            {{ number_format($result->positive_distance, 4) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            {{ number_format($result->negative_distance, 4) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900 dark:text-gray-100">
                                            {{ number_format($result->preference_value, 4) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($result->rank <= 3)
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                    Sangat Baik
                                                </span>
                                            @elseif($result->rank <= ceil($totalPPK / 2))
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
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Belum ada data penilaian kinerja</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Detail Evaluations by Period -->
            @if($periods->count() > 0)
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Detail Penilaian Per Kriteria</h3>
                    
                    <div class="mb-4">
                        <label for="period_select" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Pilih Periode:</label>
                        <select id="period_select" onchange="location.href='?period='+this.value" class="rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                            @foreach($periods as $p)
                                <option value="{{ $p }}" {{ request('period', $periods->first()) == $p ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::parse($p . '-01')->format('F Y') }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    @php
                        $selectedPeriod = request('period', $periods->first());
                        $evaluations = \App\Models\Evaluation::where('user_id', $user->id)
                            ->where('period', $selectedPeriod)
                            ->with('criteria')
                            ->get();
                        $criteria = \App\Models\Criteria::all();
                    @endphp

                    @if($evaluations->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($evaluations as $eval)
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="px-2 py-1 text-xs font-semibold rounded bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                        {{ $eval->criteria->code }}
                                    </span>
                                    <span class="px-2 py-1 text-xs font-semibold rounded {{ $eval->criteria->type === 'benefit' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                                        {{ ucfirst($eval->criteria->type) }}
                                    </span>
                                </div>
                                <h4 class="text-sm font-semibold text-gray-900 dark:text-gray-100 mb-1">
                                    {{ $eval->criteria->name }}
                                </h4>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">
                                    Bobot: {{ $eval->criteria->weight }}
                                </p>
                                <div class="flex items-center">
                                    <span class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $eval->score }}</span>
                                    <span class="ml-2 text-sm text-gray-500 dark:text-gray-400">/ 100</span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 dark:text-gray-400">Belum ada penilaian untuk periode ini.</p>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>