<nav x-data="{ open: false }">
    <aside class="fixed inset-y-0 left-0 w-72 bg-[#1E1E1E] text-white p-6 hidden lg:block">
        <h1 class="text-xl font-extrabold mb-8">🚒 SPK TOPSIS</h1>
        <div class="space-y-2">
            <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'sidebar-link sidebar-link-active' : 'sidebar-link text-gray-200' }}">🏠 Dashboard</a>
            @if(auth()->user()->isAdmin() || auth()->user()->isPimpinan())
                <a href="{{ route('personil.index') }}" class="{{ request()->routeIs('personil.*') ? 'sidebar-link sidebar-link-active' : 'sidebar-link text-gray-200' }}">👨‍🚒 Data Personil</a>
                <a href="{{ route('criteria.index') }}" class="{{ request()->routeIs('criteria.*') ? 'sidebar-link sidebar-link-active' : 'sidebar-link text-gray-200' }}">📋 Kriteria</a>
                <a href="{{ route('evaluations.index') }}" class="{{ request()->routeIs('evaluations.*') ? 'sidebar-link sidebar-link-active' : 'sidebar-link text-gray-200' }}">📝 Penilaian</a>
                <a href="{{ route('topsis.index') }}" class="{{ request()->routeIs('topsis.*') ? 'sidebar-link sidebar-link-active' : 'sidebar-link text-gray-200' }}">📊 Perhitungan TOPSIS</a>
            @endif
            @if(auth()->user()->isPersonil())
                <a href="{{ route('personil.performance') }}" class="{{ request()->routeIs('personil.performance') ? 'sidebar-link sidebar-link-active' : 'sidebar-link text-gray-200' }}">⭐ Kinerja Saya</a>
            @endif
        </div>
        <div class="mt-8 pt-6 border-t border-white/10">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full text-left sidebar-link text-red-200 hover:text-red-100">
                    🚪 Logout
                </button>
            </form>
        </div>
    </aside>
</nav>
