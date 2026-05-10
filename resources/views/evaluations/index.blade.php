<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Data Penilaian Personil') }}
            </h2>
            <a href="{{ route('evaluations.create') }}" class="btn-primary">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Penilaian
            </a>
        </div>
    </x-slot>

    <div class="space-y-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 px-4 py-3 rounded-lg bg-green-100 border border-green-400 text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Period Filter -->
            <div class="panel mb-6">
                <div class="p-6">
                    <div class="flex items-center gap-4">
                        <label for="period_filter" class="text-sm font-medium text-gray-700 dark:text-gray-300">Filter Periode:</label>
                        <select id="period_filter" onchange="location.href='?period='+this.value" class="input-modern">
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

            <!-- Evaluations Table -->
            <div class="panel">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                        Data Penilaian Personil - {{ \Carbon\Carbon::parse($period . '-01')->format('F Y') }}
                    </h3>
                    
                    <div class="overflow-x-auto">
                        <table class="table-modern">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nama Personil</th>
                                    @foreach($criteria as $c)
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            {{ $c->code }}
                                        </th>
                                    @endforeach
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($personil as $p)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10 rounded-full bg-red-500 flex items-center justify-center text-white font-bold">
                                                {{ strtoupper(substr($p->name, 0, 2)) }}
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                    {{ $p->name }}
                                                </div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                                    {{ $p->jabatan }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    @foreach($criteria as $c)
                                        @php
                                            $eval = $p->evaluations->where('criteria_id', $c->id)->first();
                                        @endphp
                                        <td class="px-6 py-4 text-center text-sm text-gray-900 dark:text-gray-100">
                                            {{ $eval ? $eval->score : '-' }}
                                        </td>
                                    @endforeach
                                    <td class="px-6 py-4 text-center">
                                        @if($p->evaluations->count() > 0)
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                Lengkap
                                            </span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                                Belum Dinilai
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center text-sm font-medium">
                                        @if($p->evaluations->count() > 0)
                                            <a href="{{ route('evaluations.edit', [$p->id, $period]) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-600">
                                                Edit
                                            </a>
                                        @else
                                            <a href="{{ route('evaluations.edit', [$p->id, $period]) }}" class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-600">
                                                Nilai
                                            </a>
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
            <div class="mt-6 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                <h4 class="text-sm font-semibold text-blue-900 dark:text-blue-100 mb-2">Keterangan Kriteria:</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-2 text-xs text-blue-800 dark:text-blue-200">
                    @foreach($criteria as $c)
                        <div>
                            <strong>{{ $c->code }}:</strong> {{ $c->name }}
                            <span class="ml-1 px-1 py-0.5 rounded text-xs {{ $c->type === 'benefit' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ ucfirst($c->type) }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>