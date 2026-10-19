<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-emerald-900 leading-tight">
            Kelola Data Siswa
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ openEdit: false, editStudent: {}, openCreate: false }">
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

            <!-- Filter Kelas, Tombol Tambah Manual & Import Excel -->
            <div class="bg-white p-6 shadow-sm sm:rounded-lg border border-emerald-100 flex flex-wrap justify-between items-center gap-4">
                <form method="GET" action="{{ route('students.index') }}" class="flex items-center gap-3">
                    <label class="text-sm font-bold text-gray-700">Pilih Kelas</label>
                    <select name="class_id" onchange="this.form.submit()" class="rounded-md border-gray-300 shadow-sm font-semibold">
                        @foreach($classes as $c)
                            <option value="{{ $c->id }}" {{ $selectedClassId == $c->id ? 'selected' : '' }}>
                                Kelas {{ $c->name }} ({{ $c->academic_year }})
                            </option>
                        @endforeach
                    </select>
                </form>

                @if($selectedClassId)
                <div class="flex items-center gap-3">
                    <!-- Tombol Tambah Siswa Manual -->
                    <button @click="openCreate = true" style="background-color: #047857; color: #ffffff;" class="px-4 py-2 text-xs font-bold rounded-lg shadow hover:opacity-90 transition-all flex items-center gap-1">
                        ➕ Tambah Siswa Baru
                    </button>

                    <!-- Form Import Excel -->
                    <form method="POST" action="{{ route('students.import') }}" enctype="multipart/form-data" class="flex items-center gap-2 bg-gray-50 p-1.5 rounded-lg border border-gray-300">
                        @csrf
                        <input type="hidden" name="class_id" value="{{ $selectedClassId }}">
                        <input type="file" name="file" accept=".xlsx, .xls" class="text-xs text-gray-600" required>
                        <button type="submit" style="background-color: #059669; color: #ffffff;" class="px-3 py-1 text-xs font-bold rounded shadow">📥 Import Excel Siswa</button>
                    </form>
                </div>
                @endif
            </div>

            <!-- Tabel Daftar Siswa -->
            @if($students)
            <div class="bg-white p-6 shadow-sm sm:rounded-lg border border-emerald-100">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Daftar Siswa</h3>
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th width="50px">NO</th>
                            <th>NIS</th>
                            <th>NISN</th>
                            <th>NAMA SISWA</th>
                            <!--<th>JK</th>-->
                            <th width="120px">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $index => $s)
                        <tr>
                            <td class="text-center font-bold text-gray-600">{{ $index + 1 }}</td>
                            <td class="font-mono text-center">{{ $s->nis }}</td>
                            <td class="font-mono text-center">{{ $s->nisn }}</td>
                            <td class="font-bold text-gray-900">{{ $s->name }}</td>
                            <!--<td class="text-center font-bold">{{ $s->gender }}</td>-->
                            <td class="text-center whitespace-nowrap">
                                <button @click="openEdit = true; editStudent = {{ json_encode($s) }}" class="text-blue-600 hover:text-blue-900 font-bold text-sm mr-2">Edit</button>
                                <form method="POST" action="{{ route('students.destroy', $s->id) }}" class="inline" onsubmit="return confirm('Hapus siswa ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 font-bold text-sm">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-gray-500">Belum ada siswa di kelas ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @endif

        </div>

        <!-- MODAL TAMBAH SISWA BARU (MANUAL) -->
        <div x-show="openCreate" class="fixed inset-0 z-50 overflow-y-auto bg-black bg-opacity-50 flex items-center justify-center p-4" x-cloak>
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6" @click.away="openCreate = false">
                <h3 class="text-lg font-bold text-gray-900 mb-4">➕ Tambah Siswa Baru</h3>
                <form action="{{ route('students.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="class_id" value="{{ $selectedClassId }}">
                    
                    <div class="mb-3">
                        <label class="block text-sm font-medium text-gray-700">Nama Lengkap Siswa</label>
                        <input type="text" name="name" placeholder="Contoh: AHMAD FARKHAN NAJIHA" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                    </div>
                    <div class="mb-3">
                        <label class="block text-sm font-medium text-gray-700">NIS</label>
                        <input type="text" name="nis" placeholder="Contoh: 240001" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                    </div>
                    <div class="mb-3">
                        <label class="block text-sm font-medium text-gray-700">NISN</label>
                        <input type="text" name="nisn" placeholder="Contoh: 0123107839" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                    </div>
                    <!--<div class="mb-4">-->
                    <!--    <label class="block text-sm font-medium text-gray-700">Jenis Kelamin</label>-->
                    <!--    <select name="gender" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>-->
                    <!--        <option value="L">Laki-laki (L)</option>-->
                    <!--        <option value="P">Perempuan (P)</option>-->
                    <!--    </select>-->
                    <!--</div>-->
                    <div class="flex justify-end gap-2">
                        <button type="button" @click="openCreate = false" class="px-4 py-2 bg-gray-200 text-gray-800 font-bold rounded-md hover:bg-gray-300">Batal</button>
                        <button type="submit" style="background-color: #059669; color: #ffffff;" class="px-4 py-2 font-bold rounded-md shadow-sm">Simpan Siswa</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- MODAL EDIT SISWA -->
        <div x-show="openEdit" class="fixed inset-0 z-50 overflow-y-auto bg-black bg-opacity-50 flex items-center justify-center p-4" x-cloak>
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6" @click.away="openEdit = false">
                <h3 class="text-lg font-bold text-gray-900 mb-4">✏️ Edit Data Siswa</h3>
                <form :action="'/students/' + editStudent.id" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="block text-sm font-medium text-gray-700">Nama Siswa</label>
                        <input type="text" name="name" x-model="editStudent.name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                    </div>
                    <div class="mb-3">
                        <label class="block text-sm font-medium text-gray-700">NIS</label>
                        <input type="text" name="nis" x-model="editStudent.nis" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                    </div>
                    <div class="mb-3">
                        <label class="block text-sm font-medium text-gray-700">NISN</label>
                        <input type="text" name="nisn" x-model="editStudent.nisn" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                    </div>
                    <!--<div class="mb-4">-->
                    <!--    <label class="block text-sm font-medium text-gray-700">Jenis Kelamin</label>-->
                    <!--    <select name="gender" x-model="editStudent.gender" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>-->
                    <!--        <option value="L">Laki-laki (L)</option>-->
                    <!--        <option value="P">Perempuan (P)</option>-->
                    <!--    </select>-->
                    <!--</div>-->
                    <div class="flex justify-end gap-2">
                        <button type="button" @click="openEdit = false" class="px-4 py-2 bg-gray-200 text-gray-800 font-bold rounded-md hover:bg-gray-300">Batal</button>
                        <button type="submit" style="background-color: #059669; color: #ffffff;" class="px-4 py-2 font-bold rounded-md shadow-sm">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>