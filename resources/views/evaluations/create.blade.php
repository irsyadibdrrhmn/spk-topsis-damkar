<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Tambah Penilaian') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('evaluations.store') }}" method="POST">
                        @csrf

                        <div class="mb-6">
                            <label for="user_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Pilih Personil Damkar *</label>
                            <select name="user_id" id="user_id" required
                                class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-blue-500 dark:focus:border-blue-600 focus:ring-blue-500 dark:focus:ring-blue-600">
                                <option value="">-- Pilih Personil --</option>
                                @foreach($personil as $p)
                                    <option value="{{ $p->id }}" {{ old('user_id') == $p->id ? 'selected' : '' }}>
                                        {{ $p->name }} - {{ $p->jabatan }}
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="period" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Periode Penilaian *</label>
                            <input type="month" name="period" id="period" value="{{ old('period', date('Y-m')) }}" required
                                class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-blue-500 dark:focus:border-blue-600 focus:ring-blue-500 dark:focus:ring-blue-600">
                            @error('period')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

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
    <div class="flex items-center gap-4">
        <input type="number" 
               name="scores[{{ $criterion->id }}]" 
               id="score_{{ $criterion->id }}"
               min="0" 
               max="100" 
               step="0.01"
               value="{{ old('scores.' . $criterion->id) }}"
               required
               class="w-32 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 focus:border-blue-500 dark:focus:border-blue-600 focus:ring-blue-500 dark:focus:ring-blue-600">
        <span class="text-sm text-gray-500 dark:text-gray-400">
            / 100 (Bobot: {{ $criterion->weight * 100 }}%)
        </span>
    </div>
    @error('scores.' . $criterion->id)
        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
    @enderror
</div>
@endforeach
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-3">
                            <a href="{{ route('evaluations.index') }}" class="px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-md hover:bg-gray-400 dark:hover:bg-gray-500 transition">
                                Batal
                            </a>
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                                Simpan Penilaian
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>