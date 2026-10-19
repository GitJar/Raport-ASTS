<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Cetak Raport ASTS
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white p-6 shadow-sm sm:rounded-lg mb-6">
                <!-- Filter Kelas -->
                <form method="GET" action="{{ route('reports.index') }}" class="flex items-center gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Kelas</label>
                        <select name="class_id" onchange="this.form.submit()" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 min-w-[200px]">
                            @foreach($classes as $c)
                                <option value="{{ $c->id }}" {{ $selectedClassId == $c->id ? 'selected' : '' }}>
                                    Kelas {{ $c->name }} ({{ $c->academic_year }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </form>
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold text-gray-800">Daftar Siswa Siap Cetak</h3>
                    @if($selectedClassId)
                        <a href="{{ route('reports.leger', $selectedClassId) }}" target="_blank" style="background-color: #0284c7; color: #ffffff;" class="px-4 py-2 text-xs font-bold rounded-lg shadow hover:opacity-90 transition-all inline-flex items-center gap-1">
                            📊 Lihat & Cetak Leger
                        </a>
                
                        <a href="{{ route('report.print-bulk', $selectedClassId) }}" target="_blank" style="background-color: #059669; color: #ffffff;" class="px-4 py-2 text-xs font-bold rounded-lg shadow hover:opacity-90 transition-all inline-flex items-center gap-1">
                            🖨️ Cetak Raport 1 Kelas Massal
                        </a>
                    @endif
                </div>
            </div>

            <!-- Tabel Daftar Siswa Siap Cetak -->
            <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold text-gray-800">Daftar Siswa Siap Cetak</h3>
                </div>

                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">NIS / NISN</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Siswa</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Aksi Cetak</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($students as $index => $s)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $index + 1 }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $s->nis }} / {{ $s->nisn }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-800">{{ $s->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <a href="{{ route('report.print', $s->id) }}" target="_blank" style="background-color: #16a34a; color: #ffffff;" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 transition ease-in-out duration-150">
                                    🖨️ Cetak Raport
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">Belum ada siswa di kelas ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</x-app-layout>