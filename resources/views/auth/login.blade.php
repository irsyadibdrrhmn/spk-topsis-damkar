<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login SPK TOPSIS Damkar</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex items-center justify-center bg-gradient-to-br from-[#1E1E1E] via-[#B22222] to-[#DC2626] p-4">
<div class="absolute inset-0 opacity-20" style="background-image: radial-gradient(#fff 1px, transparent 1px);background-size: 20px 20px;"></div>
<div class="glass-card max-w-5xl w-full grid md:grid-cols-2 overflow-hidden">
    <div class="p-10 text-white bg-gradient-to-br from-[#1E1E1E]/90 to-[#B22222]/90">
        <h1 class="text-3xl font-extrabold mb-4">SPK TOPSIS DAMKAR</h1>
        <p class="text-white/90 leading-relaxed">Sistem Pendukung Keputusan Pemberangkatan Diklat Personil UPT Pemadam Kebakaran Kabupaten Kuningan.</p>
    </div>
    <div class="p-10 bg-white">
        <h2 class="text-2xl font-bold text-[#1E1E1E] mb-6">Masuk ke Sistem</h2>
        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf
            <div><label class="label-modern">Email</label><input class="input-modern" type="email" name="email" value="{{ old('email') }}" required></div>
            <div><label class="label-modern">Kata Sandi</label><input class="input-modern" type="password" name="password" required></div>
            <label class="text-sm text-gray-600 flex items-center gap-2"><input type="checkbox" name="remember" class="rounded border-gray-300"> Ingat saya</label>
            <button class="btn-primary w-full">Masuk</button>
        </form>
    </div>
</div>
</body></html>
