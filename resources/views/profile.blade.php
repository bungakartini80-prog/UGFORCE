@extends('layouts.app')

@section('content')
<div class="container py-8 max-w-4xl">
    <!-- Header Page -->
    <div class="mb-8 text-center md:text-left reveal">
        <h2 class="text-3xl font-extrabold text-slate-900 dark:text-white flex items-center justify-center md:justify-start gap-3">
            <div class="w-10 h-10 bg-sky-500/10 text-sky-500 rounded-xl flex items-center justify-center">
                <i class="bi bi-person-gear"></i>
            </div>
            Pengaturan Profil Akun
        </h2>
        <p class="text-slate-500 dark:text-white/60 text-sm mt-2">
            Perbarui data diri, kata sandi, dan foto profil Anda secara aman.
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Kiri: Preview Profil & Avatar -->
        <div class="md:col-span-1 flex flex-col gap-6 reveal d-1">
            <div class="glass-card p-6 flex flex-col items-center text-center gap-4">
                <div class="relative w-32 h-32 rounded-full overflow-hidden border-4 border-white dark:border-white/10 shadow-lg group">
                    @if($user->avatar)
                        <img id="avatar-preview" src="{{ $user->avatar }}" alt="Avatar" class="w-full h-full object-cover">
                    @else
                        <div id="avatar-placeholder-initial" class="w-full h-full bg-gradient-to-tr from-sky-400 to-blue-600 text-white font-black text-4xl flex items-center justify-center">
                            {{ strtoupper(substr($user->name, 0, 2)) }}
                        </div>
                        <img id="avatar-preview" src="" alt="Avatar" class="w-full h-full object-cover hidden">
                    @endif
                </div>

                <div>
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-0">{{ $user->name }}</h3>
                    <p class="text-xs font-semibold text-slate-400 dark:text-white/40 uppercase tracking-widest mt-1 capitalize mb-0">
                        {{ $user->role === 'lecturer' ? 'Dosen' : 'Mahasiswa' }}
                    </p>
                </div>

                <div class="w-full border-t border-slate-200 dark:border-white/10 pt-4 flex flex-col gap-2.5 text-left text-xs font-semibold text-slate-500 dark:text-white/60">
                    <div class="flex justify-between">
                        <span>Email:</span>
                        <span class="text-slate-800 dark:text-white font-mono">{{ $user->email }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Status Wajah:</span>
                        @if($user->face_signature)
                            <span class="text-green-500 flex items-center gap-1"><i class="bi bi-patch-check-fill"></i> Terdaftar</span>
                        @else
                            <span class="text-amber-500 flex items-center gap-1"><i class="bi bi-exclamation-triangle-fill"></i> Belum Ada</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Kanan: Form Edit -->
        <div class="md:col-span-2 reveal d-2">
            <div class="glass-card p-6 md:p-8">
                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="flex flex-col gap-6 m-0">
                    @csrf

                    <!-- Ganti Foto Profil -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-white/60 mb-2">Foto Profil</label>
                        <div class="flex items-center gap-4">
                            <div class="flex-grow">
                                <label for="avatar_file" class="w-full flex flex-col items-center justify-center px-4 py-6 border-2 border-dashed border-slate-200 dark:border-white/10 rounded-2xl cursor-pointer hover:border-sky-500 dark:hover:border-sky-400 transition bg-slate-50/50 dark:bg-white/5">
                                    <i class="bi bi-cloud-arrow-up text-3xl text-slate-400 dark:text-white/60 mb-2"></i>
                                    <span class="text-xs font-bold text-slate-700 dark:text-white">Pilih file foto baru</span>
                                    <span class="text-[10px] text-slate-400 dark:text-white/40 mt-1">JPEG, PNG maks 2MB</span>
                                </label>
                                <input type="file" id="avatar_file" name="avatar_file" class="hidden" accept="image/*" onchange="previewImage(this)">
                            </div>
                        </div>
                    </div>

                    <!-- Input Nama -->
                    <div>
                        <label for="name" class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-white/60 mb-2">Nama Lengkap</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400 dark:text-white/40">
                                <i class="bi bi-person"></i>
                            </span>
                            <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required
                                   class="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-200 dark:border-white/10 bg-white/60 dark:bg-white/5 text-slate-900 dark:text-white font-medium text-sm focus:border-sky-500 focus:ring-1 focus:ring-sky-500 outline-none transition">
                        </div>
                    </div>

                    <!-- Input Email (Disabled) -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-white/60 mb-2">Alamat Email (Akses Login)</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400 dark:text-white/30">
                                <i class="bi bi-envelope"></i>
                            </span>
                            <input type="email" value="{{ $user->email }}" disabled
                                   class="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-200 dark:border-white/10 bg-slate-100 dark:bg-white/5 opacity-50 text-slate-500 dark:text-white/40 font-mono text-sm cursor-not-allowed outline-none">
                        </div>
                    </div>

                    <hr class="border-slate-200 dark:border-white/10 my-2">

                    <!-- Password Ganti -->
                    <div>
                        <h4 class="text-sm font-bold text-slate-900 dark:text-white mb-2">Ubah Kata Sandi (Opsional)</h4>
                        <p class="text-[11px] text-slate-400 dark:text-white/60 mb-4">Biarkan kosong jika Anda tidak ingin mengubah kata sandi lama Anda.</p>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="password" class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-white/60 mb-2">Password Baru</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400 dark:text-white/40">
                                        <i class="bi bi-lock"></i>
                                    </span>
                                    <input type="password" id="password" name="password" placeholder="Minimal 6 karakter"
                                           class="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-200 dark:border-white/10 bg-white/60 dark:bg-white/5 text-slate-900 dark:text-white font-medium text-sm focus:border-sky-500 focus:ring-1 focus:ring-sky-500 outline-none transition">
                                </div>
                            </div>
                            <div>
                                <label for="password_confirmation" class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-white/60 mb-2">Ulangi Password Baru</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400 dark:text-white/40">
                                        <i class="bi bi-shield-lock"></i>
                                    </span>
                                    <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Konfirmasi password"
                                           class="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-200 dark:border-white/10 bg-white/60 dark:bg-white/5 text-slate-900 dark:text-white font-medium text-sm focus:border-sky-500 focus:ring-1 focus:ring-sky-500 outline-none transition">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tombol Simpan -->
                    <div class="flex justify-end gap-3 mt-4">
                        <a href="{{ url('/dashboard') }}" class="px-5 py-3 rounded-xl border border-slate-200 dark:border-white/10 text-xs font-bold text-slate-600 dark:text-white hover:bg-slate-100 dark:hover:bg-white/5 transition text-decoration-none">
                            Batal
                        </a>
                        <button type="submit" class="px-6 py-3 rounded-xl bg-gradient-to-r from-sky-400 to-blue-600 dark:from-sky-400 dark:to-yellow-400 text-white dark:text-slate-900 font-extrabold text-xs shadow-lg shadow-sky-500/20 hover:scale-105 hover:shadow-sky-500/35 dark:hover:shadow-yellow-400/20 transition-all duration-300 border-0">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                const preview = document.getElementById('avatar-preview');
                const initial = document.getElementById('avatar-placeholder-initial');
                
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                if (initial) {
                    initial.classList.add('hidden');
                }
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection
