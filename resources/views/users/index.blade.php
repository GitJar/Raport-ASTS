<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-emerald-900 leading-tight">
            Manajemen Pengguna & Pengajar
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ openEdit: false, editUser: {}, openReset: false, resetUser: {} }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            @if(session('success'))
                <div class="p-4 bg-emerald-100 border border-emerald-400 text-emerald-700 font-semibold rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="p-4 bg-rose-100 border border-rose-400 text-rose-700 font-semibold rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                <!-- Form Tambah User -->
                <div class="bg-white p-6 shadow-sm rounded-xl border border-emerald-100 h-fit">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">➕ Tambah Pengguna Baru</h3>
                    <form method="POST" action="{{ route('users.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-700">Nama Lengkap & Gelar</label>
                            <input type="text" name="name" placeholder="Contoh: SRI PRATIWI, S.Pd" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                        </div>
                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-700">NIP / NIK</label>
                            <input type="text" name="nip_nik" placeholder="Contoh: 197807122005012005" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        </div>
                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-700">Email Login</label>
                            <input type="email" name="email" placeholder="email@mtsn3purworejo.sch.id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                        </div>
                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-700">Password Awal</label>
                            <input type="password" name="password" placeholder="Minimal 6 Karakter" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Role / Hak Akses</label>
                            <select name="role" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm font-semibold" required>
                                <option value="guru_mapel">Guru Mata Pelajaran</option>
                                <option value="wali_kelas">Wali Kelas</option>
                                <option value="guru_bk">Guru BK</option>
                                <option value="kepala_madrasah">Kepala Madrasah</option>
                                <option value="admin">Administrator (Akses Penuh)</option>
                            </select>
                        </div>
                        <button type="submit" style="background-color: #059669; color: #ffffff;" class="w-full py-2.5 font-bold rounded-md shadow-sm hover:opacity-90">
                            Simpan Pengguna
                        </button>
                    </form>
                </div>

                <!-- Tabel Daftar User (Diset Rapi & Overflow Auto) -->
                <div class="md:col-span-2 bg-white p-6 shadow-sm rounded-xl border border-emerald-100 overflow-hidden">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Daftar Akun Pengguna</h3>
                    
                    <div class="overflow-x-auto w-full">
                        <table class="w-full table-fixed border-collapse border border-gray-300">
                            <thead>
                                <tr>
                                    <th class="w-2/5">NAMA & NIP</th>
                                    <th class="w-2/5">EMAIL</th>
                                    <th class="w-1/5">ROLE</th>
                                    <th class="w-1/5">AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $u)
                                <tr>
                                    <td class="font-bold text-gray-800 break-words">
                                        {{ $u->name }} <br>
                                        <span class="text-xs text-gray-500 font-normal">NIP: {{ $u->nip_nik ?? '-' }}</span>
                                    </td>
                                    <td class="text-gray-600 break-all text-xs">
                                        {{ $u->email }}
                                    </td>
                                    <td class="text-center">
                                        <span class="px-2 py-1 text-[10px] font-bold rounded-full block text-center whitespace-nowrap
                                            {{ $u->role === 'admin' ? 'bg-purple-100 text-purple-800' : '' }}
                                            {{ $u->role === 'wali_kelas' ? 'bg-blue-100 text-blue-800' : '' }}
                                            {{ $u->role === 'guru_mapel' ? 'bg-green-100 text-green-800' : '' }}
                                            {{ $u->role === 'guru_bk' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                            {{ $u->role === 'kepala_madrasah' ? 'bg-red-100 text-red-800' : '' }}">
                                            {{ strtoupper(str_replace('_', ' ', $u->role)) }}
                                        </span>
                                    </td>
                                    <td class="text-center whitespace-nowrap">
                                        <button @click="openEdit = true; editUser = {{ json_encode($u) }}" class="text-blue-600 hover:text-blue-900 font-bold text-xs mr-1">Edit</button>
                                        
                                        @if($u->id !== auth()->id())
                                        <form method="POST" action="{{ route('users.destroy', $u->id) }}" class="inline" onsubmit="return confirm('Hapus pengguna ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 font-bold text-xs">Hapus</button>
                                        </form>
                                        @else
                                        <span class="text-[10px] text-gray-400 italic">Aktif</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

        </div>

        <!-- MODAL EDIT PENGGUNA -->
        <div x-show="openEdit" class="fixed inset-0 z-50 overflow-y-auto bg-black bg-opacity-50 flex items-center justify-center p-4" x-cloak>
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6" @click.away="openEdit = false">
                <h3 class="text-lg font-bold text-gray-900 mb-4">✏️ Edit Data Pengguna</h3>
                <form :action="'/users/' + editUser.id" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="block text-sm font-medium text-gray-700">Nama Lengkap & Gelar</label>
                        <input type="text" name="name" x-model="editUser.name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                    </div>
                    <div class="mb-3">
                        <label class="block text-sm font-medium text-gray-700">NIP / NIK</label>
                        <input type="text" name="nip_nik" x-model="editUser.nip_nik" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    </div>
                    <div class="mb-3">
                        <label class="block text-sm font-medium text-gray-700">Email Login</label>
                        <input type="email" name="email" x-model="editUser.email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                    </div>
                    <div class="mb-3">
                        <label class="block text-sm font-medium text-gray-700">Role / Hak Akses</label>
                        <select name="role" x-model="editUser.role" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm font-semibold" required>
                            <option value="guru_mapel">Guru Mata Pelajaran</option>
                            <option value="wali_kelas">Wali Kelas</option>
                            <option value="guru_bk">Guru BK</option>
                            <option value="kepala_madrasah">Kepala Madrasah</option>
                            <option value="admin">Administrator (Akses Penuh)</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Reset Password (Opsional)</label>
                        <input type="password" name="password" placeholder="Kosongkan jika tidak diubah" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    </div>
                    <div class="flex justify-end gap-2">
                        <button type="button" @click="openEdit = false" class="px-4 py-2 bg-gray-200 text-gray-800 font-bold rounded-md hover:bg-gray-300">Batal</button>
                        <button type="submit" style="background-color: #059669; color: #ffffff;" class="px-4 py-2 font-bold rounded-md shadow-sm">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>