<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-emerald-900 leading-tight">
            Kelola Data Kelas
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ openEdit: false, editClass: {} }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            @if(session('success'))
                <div class="p-4 bg-emerald-100 border border-emerald-400 text-emerald-700 font-semibold rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                <!-- Form Tambah Kelas -->
                <div class="bg-white p-6 shadow-sm sm:rounded-lg border border-emerald-100">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">➕ Tambah Kelas Baru</h3>
                    <form method="POST" action="{{ route('classes.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-700">Nama Kelas</label>
                            <input type="text" name="name" placeholder="Contoh: VIIILA / VIIIB" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                        </div>
                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-700">Fase</label>
                            <input type="text" name="phase" value="D" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                        </div>
                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-700">Tahun Ajaran</label>
                            <input type="text" name="academic_year" value="2026/2027" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                        </div>
                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-700">Semester</label>
                            <select name="semester" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                <option value="Ganjil">Ganjil</option>
                                <option value="Genap">Genap</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Wali Kelas</label>
                            <select name="teacher_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                <option value="">-- Pilih Wali Kelas --</option>
                                @foreach($teachers as $t)
                                    <option value="{{ $t->id }}">{{ $t->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" style="background-color: #059669; color: #ffffff;" class="w-full py-2 font-bold rounded-md shadow-sm hover:opacity-90">
                            Simpan Kelas
                        </button>
                    </form>
                </div>

                <!-- Tabel Daftar Kelas -->
                <div class="md:col-span-2 bg-white p-6 shadow-sm sm:rounded-lg border border-emerald-100">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Daftar Kelas Terdaftar</h3>
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th>KELAS</th>
                                <th>WALI KELAS</th>
                                <th>JML SISWA</th>
                                <th>STATUS NILAI</th>
                                <th>AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($classes as $c)
                            <tr>
                                <td class="font-bold text-gray-800">
                                    {{ $c->name }} <br>
                                    <span class="text-xs text-gray-500 font-normal">Fase {{ $c->phase }} | {{ $c->academic_year }} ({{ $c->semester }})</span>
                                </td>
                                <td class="text-gray-600">
                                    {{ $c->teacher->name ?? '-' }}
                                </td>
                                <td class="text-center font-bold text-indigo-600">
                                    {{ $c->students->count() }}
                                </td>
                                <td class="text-center">
                                    <form method="POST" action="{{ route('classes.toggle-lock', $c->id) }}">
                                        @csrf
                                        @if($c->is_locked)
                                            <button type="submit" class="px-3 py-1 bg-red-100 text-red-800 text-xs font-bold rounded-full hover:bg-red-200">
                                                🔒 Dikunci
                                            </button>
                                        @else
                                            <button type="submit" class="px-3 py-1 bg-green-100 text-green-800 text-xs font-bold rounded-full hover:bg-green-200">
                                                🔓 Terbuka
                                            </button>
                                        @endif
                                    </form>
                                </td>
                                <td class="text-center whitespace-nowrap">
                                    <!-- Tombol Edit Modal -->
                                    <button @click="openEdit = true; editClass = {{ json_encode($c) }}" class="text-blue-600 hover:text-blue-900 font-bold text-sm mr-2">
                                        Edit
                                    </button>
                                    <form method="POST" action="{{ route('classes.destroy', $c->id) }}" class="inline" onsubmit="return confirm('Hapus kelas ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 font-bold text-sm">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-gray-500">Belum ada data kelas.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>

        </div>

        <!-- MODAL EDIT KELAS -->
        <div x-show="openEdit" class="fixed inset-0 z-50 overflow-y-auto bg-black bg-opacity-50 flex items-center justify-center p-4" x-cloak>
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6" @click.away="openEdit = false">
                <h3 class="text-lg font-bold text-gray-900 mb-4">✏️ Edit Data Kelas</h3>
                <form :action="'/classes/' + editClass.id" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="block text-sm font-medium text-gray-700">Nama Kelas</label>
                        <input type="text" name="name" x-model="editClass.name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                    </div>
                    <div class="mb-3">
                        <label class="block text-sm font-medium text-gray-700">Fase</label>
                        <input type="text" name="phase" x-model="editClass.phase" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                    </div>
                    <div class="mb-3">
                        <label class="block text-sm font-medium text-gray-700">Tahun Ajaran</label>
                        <input type="text" name="academic_year" x-model="editClass.academic_year" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                    </div>
                    <div class="mb-3">
                        <label class="block text-sm font-medium text-gray-700">Semester</label>
                        <select name="semester" x-model="editClass.semester" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                            <option value="Ganjil">Ganjil</option>
                            <option value="Genap">Genap</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Wali Kelas</label>
                        <select name="teacher_id" x-model="editClass.teacher_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            <option value="">-- Pilih Wali Kelas --</option>
                            @foreach($teachers as $t)
                                <option value="{{ $t->id }}">{{ $t->name }}</option>
                            @endforeach
                        </select>
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