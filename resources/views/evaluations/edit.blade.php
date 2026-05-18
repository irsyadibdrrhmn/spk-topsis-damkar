<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Penilaian') }} - {{ $personil->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Personil Info -->
                    <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4 mb-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-12 w-12 rounded-full bg-blue-500 flex items-center justify-center text-white font-bold text-lg">
                                {{ strtoupper(substr($personil->name, 0, 2)) }}
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $personil->name }}</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $personil->jabatan }} - NIP: {{ $personil->nip }}</p>
                            </div>
                        </div>
                        <div class="mt-3 text-sm text-blue-800 dark:text-blue-200">
                            <strong>Periode:</strong> {{ \Carbon\Carbon::parse($period . '-01')->format('F Y') }}
                        </div>
                    </div>

                    <form action="{{ route('evaluations.update', [$personil->id, $period]) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Nilai Kriteria</h3>
                            <div class="space-y-4">
                                @foreach($criteria as $criterion)
<div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
    <div class="flex items-start justify-between mb-2">
        <div>
            <label for="score_{{ $criterion->id }}" class="text-sm font-medium text-gray-900 dark:text-gray-100">
                {{ $criterion->code }} - {{ $criterion->name }} ({{ $criterion->weight * 100 }}%)
            </label>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                {{ $criterion->description }}
            </p>
        </div>
        <span class="px-2 py-1 text-xs font-semibold rounded bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
            Benefit
        </span>
    </div>
    @php
        $options = $scoreOptions[$criterion->name] ?? [];
        $selectedScore = old('scores.' . $criterion->id, $evaluations[$criterion->id]->score ?? '');
    @endphp
    <div class="space-y-2">
        <select name="scores[{{ $criterion->id }}]"
                id="score_{{ $criterion->id }}"
                required
                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 focus:border-blue-500 dark:focus:border-blue-600 focus:ring-blue-500 dark:focus:ring-blue-600">
            <option value="">-- Pilih kategori nilai {{ $criterion->name }} --</option>
            @foreach($options as $option)
                <option value="{{ $option['value'] }}" {{ (string) $selectedScore === (string) $option['value'] ? 'selected' : '' }}>
                    Nilai {{ $option['value'] }} - {{ $option['label'] }}
                </option>
            @endforeach
        </select>
        <p class="text-xs text-gray-500 dark:text-gray-400">
            Gunakan pilihan kategori agar tidak perlu mengisi angka manual. (Bobot: {{ $criterion->weight * 100 }}%)
        </p>
    </div>
    @error('scores.' . $criterion->id)
        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
    @enderror
</div>
@endforeach
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-3">
                            <a href="{{ route('evaluations.index', ['period' => $period]) }}" class="px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-md hover:bg-gray-400 dark:hover:bg-gray-500 transition">
                                Batal
                            </a>
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                                Update Penilaian
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
