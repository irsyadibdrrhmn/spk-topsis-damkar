<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard Personil') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Profile Card -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-20 w-20 rounded-full bg-red-500 flex items-center justify-center text-white text-3xl font-bold">
                            {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                        </div>
                        <div class="ml-6">
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ auth()->user()->name }}</h3>
                            <p class="text-gray-500 dark:text-gray-400">{{ auth()->user()->jabatan }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">NIP: {{ auth()->user()->nip }}</p>
                        </div>
                    </div>
                </div>
            </div>

            @php
                $latestResult = \App\Models\TopsisResult::where('user_id', auth()->id())
                    ->orderBy('period', 'desc')
                    ->first();
                $totalPersonil = \App\Models\User::where('role', 'personil')->count();
            @endphp

            <!-- Performance Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-red-500 rounded-md p-3">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm text-gray-500 dark:text-gray-400">Peringkat Terakhir</p>
                                <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100">
                                    {{ $latestResult ? $latestResult->rank . ' / ' . $totalPersonil : '-' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm text-gray-500 dark:text-gray-400">Nilai Preferensi</p>
                                <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100">
                                    {{ $latestResult ? number_format($latestResult->preference_value, 4) : '-' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-yellow-500 rounded-md p-3">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm text-gray-500 dark:text-gray-400">Periode Terakhir</p>
                                <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100">
                                    {{ $latestResult ? \Carbon\Carbon::parse($latestResult->period . '-01')->format('M Y') : '-' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Performance Card -->
            @if($latestResult)
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Status Penilaian Terbaru</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Jarak Positif & Negatif -->
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                            <h4 class="text-sm font-semibold text-gray-900 dark:text-gray-100 mb-3">Jarak dari Solusi Ideal</h4>
                            <div class="space-y-2">
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-600 dark:text-gray-300">Jarak Positif (D+):</span>
                                    <span class="text-sm font-mono font-semibold text-gray-900 dark:text-gray-100">{{ number_format($latestResult->positive_distance, 6) }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-600 dark:text-gray-300">Jarak Negatif (D-):</span>
                                    <span class="text-sm font-mono font-semibold text-gray-900 dark:text-gray-100">{{ number_format($latestResult->negative_distance, 6) }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Performance Category -->
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                            <h4 class="text-sm font-semibold text-gray-900 dark:text-gray-100 mb-3">Kategori Kinerja</h4>
                            <div>
                                @if($latestResult->rank <= 3)
                                    <span class="px-3 py-2 inline-flex text-sm font-semibold rounded-lg bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                        ✓ Sangat Baik
                                    </span>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">Anda termasuk dalam 3 besar personil dengan kinerja terbaik</p>
                                @elseif($latestResult->rank <= ceil($totalPersonil / 2))
                                    <span class="px-3 py-2 inline-flex text-sm font-semibold rounded-lg bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                        → Baik
                                    </span>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">Kinerja Anda tergolong baik, pertahankan prestasi</p>
                                @else
                                    <span class="px-3 py-2 inline-flex text-sm font-semibold rounded-lg bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                        ⚠ Perlu Peningkatan
                                    </span>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">Tingkatkan kinerja Anda untuk periode berikutnya</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Performance History & Quick Links -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <a href="{{ route('personil.performance') }}" class="block p-6 bg-white dark:bg-gray-800 rounded-lg shadow-sm hover:shadow-md transition">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-red-500 rounded-md p-3">
                            <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Riwayat Kinerja</h4>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Lihat semua riwayat penilaian Anda</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('profile.edit') }}" class="block p-6 bg-white dark:bg-gray-800 rounded-lg shadow-sm hover:shadow-md transition">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
                            <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Profil Saya</h4>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Kelola data profil dan keamanan akun</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
