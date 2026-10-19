<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-emerald-900 leading-tight flex items-center gap-2">
            📋 Input Presensi & Catatan Wali Kelas
        </h2>
    </x-slot>

    <div class="py-8 bg-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            @if(session('success'))
                <div class="p-4 bg-emerald-100 border-l-4 border-emerald-600 text-emerald-800 font-semibold rounded-r-lg shadow-sm">
                    ✅ {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="p-4 bg-rose-100 border-l-4 border-rose-600 text-rose-800 font-semibold rounded-r-lg shadow-sm">
                    ⚠️ {{ session('error') }}
                </div>
            @endif

            <!-- Card Filter Kelas -->
            <div class="bg-white rounded-xl shadow-md p-6 border border-emerald-100">
                <form method="GET" action="{{ route('summaries.index') }}" class="flex flex-wrap gap-4 items-end">
                    <div class="min-w-[240px]">
                        <label class="block text-xs font-bold text-emerald-900 uppercase tracking-wider mb-1">Pilih Kelas</label>
                        <select name="class_id" onchange="this.form.submit()" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 font-semibold">
                            @foreach($classes as $c)
                                <option value="{{ $c->id }}" {{ $selectedClassId == $c->id ? 'selected' : '' }}>
                                    Kelas {{ $c->name }} ({{ $c->academic_year }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>

            @if($students)
            <div class="bg-white rounded-xl shadow-md p-6 border border-emerald-100">
                
                <!-- Area Download & Upload Excel Presensi -->
                <div class="flex flex-wrap justify-between items-center mb-6 gap-4 border-b border-gray-200 pb-4">
                    <div>
                        <h3 class="text-xl font-bold text-gray-800">Daftar Presensi & Catatan Siswa</h3>
                        <p class="text-sm text-emerald-700 font-bold mt-1">
                            Kelas: {{ $classes->firstWhere('id', $selectedClassId)->name ?? '-' }}
                        </p>
                    </div>

                    <div class="flex flex-wrap items-center gap-3">
                        <!-- Download Template -->
                        <a href="{{ route('summaries.template', ['class_id' => $selectedClassId]) }}" 
                           style="background-color: #0284c7; color: #ffffff;" 
                           class="px-4 py-2 text-xs font-bold rounded-lg shadow hover:opacity-90 transition-all inline-flex items-center">
                            📥 Download Templat Excel
                        </a>

                        <!-- Upload Form -->
                        <form method="POST" action="{{ route('summaries.import') }}" enctype="multipart/form-data" class="flex items-center gap-2 bg-gray-50 p-1.5 rounded-lg border border-gray-300">
                            @csrf
                            <input type="hidden" name="class_id" value="{{ $selectedClassId }}">
                            <input type="file" name="file" accept=".xlsx, .xls" class="text-xs text-gray-600 file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:bg-emerald-100 file:text-emerald-800 hover:file:bg-emerald-200 font-semibold" required>
                            <button type="submit" style="background-color: #059669; color: #ffffff;" class="px-3 py-1 text-xs font-bold rounded shadow hover:opacity-90">
                                📤 Upload Excel
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Form Manual Input -->
                <form method="POST" action="{{ route('summaries.store') }}">
                    @csrf
                    <div class="overflow-x-auto mb-6">
                        <table class="border-2 border-emerald-800">
                            <thead>
                                <tr>
                                    <th width="50px">No</th>
                                    <th width="250px">Nama Lengkap Siswa</th>
                                    <th width="90px">Sakit</th>
                                    <th width="90px">Izin</th>
                                    <th width="90px">Alfa</th>
                                    <th>Catatan Wali Kelas</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($students as $index => $s)
                                @php $sum = $summaries[$s->id] ?? null; @endphp
                                <tr>
                                    <td class="text-center font-bold text-gray-600">{{ $index + 1 }}</td>
                                    <td class="font-bold text-gray-900">{{ $s->name }}</td>
                                    <td class="text-center">
                                        <input type="number" name="summaries[{{ $s->id }}][sick]" value="{{ $sum->sick_count ?? 0 }}" min="0" class="w-16 text-center rounded border-gray-400 font-bold focus:bg-yellow-50">
                                    </td>
                                    <td class="text-center">
                                        <input type="number" name="summaries[{{ $s->id }}][permission]" value="{{ $sum->permission_count ?? 0 }}" min="0" class="w-16 text-center rounded border-gray-400 font-bold focus:bg-yellow-50">
                                    </td>
                                    <td class="text-center">
                                        <input type="number" name="summaries[{{ $s->id }}][unexcused]" value="{{ $sum->unexcused_count ?? 0 }}" min="0" class="w-16 text-center rounded border-gray-400 font-bold focus:bg-yellow-50">
                                    </td>
                                    <td>
                                        <input type="text" name="summaries[{{ $s->id }}][notes]" value="{{ $sum->teacher_notes ?? '' }}" placeholder="Contoh: Tingkatkan motivasi belajar" class="w-full rounded border-gray-400 text-sm font-semibold focus:bg-yellow-50">
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" style="background-color: #059669; color: #ffffff;" class="px-8 py-3 text-base font-extrabold rounded-xl shadow-lg hover:bg-emerald-700 transition-all flex items-center gap-2">
                            💾 Simpan Presensi & Catatan
                        </button>
                    </div>
                </form>
            </div>
            @endif

        </div>
    </div>
</x-app-layout>