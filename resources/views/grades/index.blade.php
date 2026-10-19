<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-emerald-900 leading-tight flex items-center gap-2">
            📊 Input Nilai Asesmen Sumatif Tengah Semester (ASTS)
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

            <!-- Card Filter Kelas & Mapel -->
            <div class="bg-white rounded-xl shadow-md p-6 border border-emerald-100">
                <form method="GET" action="{{ route('grades.index') }}" class="flex flex-wrap gap-4 items-end">
                    <div class="flex-1 min-w-[200px]">
                        <label class="block text-xs font-bold text-emerald-900 uppercase tracking-wider mb-1">Pilih Kelas</label>
                        <select name="class_id" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 font-semibold">
                            @foreach($classes as $c)
                                <option value="{{ $c->id }}" {{ $selectedClassId == $c->id ? 'selected' : '' }}>
                                    Kelas {{ $c->name }} ({{ $c->academic_year }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex-2 min-w-[280px]">
                        <label class="block text-xs font-bold text-emerald-900 uppercase tracking-wider mb-1">Pilih Mata Pelajaran</label>
                        <select name="subject_id" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 font-semibold">
                            @foreach($subjects as $s)
                                <option value="{{ $s->id }}" {{ $selectedSubjectId == $s->id ? 'selected' : '' }}>
                                    {{ $s->parent ? $s->parent->name . ' ➔ ' : '' }}{{ $s->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" style="background-color: #047857; color: #ffffff;" class="px-6 py-2.5 font-bold rounded-lg shadow-md hover:bg-emerald-800 transition-all flex items-center gap-2">
                        🔍 Tampilkan Siswa
                    </button>
                </form>
            </div>

            @if($students)
            <div class="bg-white rounded-xl shadow-md p-6 border border-emerald-100">
                
                <!-- Area Header Table & Import/Export -->
                <div class="flex flex-wrap justify-between items-center mb-6 gap-4 border-b border-gray-200 pb-4">
                    <div>
                        <h3 class="text-xl font-bold text-gray-800">Daftar Siswa & Input Nilai</h3>
                        @php $currentSubject = $subjects->firstWhere('id', $selectedSubjectId); @endphp
                        <p class="text-sm text-emerald-700 font-bold mt-1">
                            Mata Pelajaran: {{ $currentSubject->parent ? $currentSubject->parent->name . ' ➔ ' : '' }}{{ $currentSubject->name ?? '-' }}
                        </p>
                    </div>

                    <div class="flex flex-wrap items-center gap-3">
                        <a href="{{ route('grades.template', ['class_id' => $selectedClassId, 'subject_id' => $selectedSubjectId]) }}" 
                           style="background-color: #0284c7; color: #ffffff;" 
                           class="px-4 py-2 text-xs font-bold rounded-lg shadow hover:opacity-90 transition-all">
                            📥 Download Templat Excel
                        </a>

                        @if(!$currentClass || !$currentClass->is_locked || auth()->user()->role === 'admin')
                        <form method="POST" action="{{ route('grades.import') }}" enctype="multipart/form-data" class="flex items-center gap-2 bg-gray-50 p-1.5 rounded-lg border border-gray-300">
                            @csrf
                            <input type="hidden" name="class_id" value="{{ $selectedClassId }}">
                            <input type="hidden" name="subject_id" value="{{ $selectedSubjectId }}">
                            <input type="file" name="file" accept=".xlsx, .xls" class="text-xs text-gray-600 file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:bg-emerald-100 file:text-emerald-800 hover:file:bg-emerald-200 font-semibold" required>
                            <button type="submit" style="background-color: #059669; color: #ffffff;" class="px-3 py-1 text-xs font-bold rounded shadow hover:opacity-90">
                                📤 Upload
                            </button>
                        </form>
                        @endif

                        @if($currentClass && $currentClass->is_locked)
                            <span class="px-3 py-1 bg-rose-100 text-rose-800 text-xs font-bold rounded-full border border-rose-300">🔒 Status Dikunci</span>
                        @else
                            <span class="px-3 py-1 bg-emerald-100 text-emerald-800 text-xs font-bold rounded-full border border-emerald-300">🔓 Status Terbuka</span>
                        @endif
                    </div>
                </div>

                <!-- FORM TABEL INPUT MANUAL DENGAN BORDER TEGAS -->
                <form method="POST" action="{{ route('grades.store') }}">
                    @csrf
                    <input type="hidden" name="class_id" value="{{ $selectedClassId }}">
                    <input type="hidden" name="subject_id" value="{{ $selectedSubjectId }}">

                    <div class="overflow-x-auto mb-6">
                        <table class="border-2 border-emerald-800">
                            <thead>
                                <tr>
                                    <th width="50px">No</th>
                                    <th width="180px">NIS / NISN</th>
                                    <th>Nama Lengkap Siswa</th>
                                    <th width="160px">Nilai ASTS (0-100)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($students as $index => $student)
                                <tr>
                                    <td class="text-center font-bold text-gray-600">{{ $index + 1 }}</td>
                                    <td class="font-mono text-xs text-gray-600 text-center">{{ $student->nis }} / {{ $student->nisn }}</td>
                                    <td class="font-bold text-gray-900">{{ $student->name }}</td>
                                    <td class="text-center">
                                        <input type="number" 
                                               name="scores[{{ $student->id }}]" 
                                               value="{{ $grades[$student->id] ?? '' }}" 
                                               min="0" max="100" 
                                               placeholder="0"
                                               class="w-20 text-center rounded border-gray-400 font-extrabold text-base focus:bg-yellow-50 focus:border-emerald-600 disabled:bg-gray-200 disabled:text-gray-400"
                                               {{ $currentClass && $currentClass->is_locked && auth()->user()->role !== 'admin' ? 'disabled' : '' }}>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if(!$currentClass || !$currentClass->is_locked || auth()->user()->role === 'admin')
                    <div class="flex justify-end">
                        <button type="submit" style="background-color: #059669; color: #ffffff;" class="px-6 py-2 font-bold rounded-lg shadow-md hover:bg-emerald-800 transition-all flex items-center gap-2">
                            💾 Simpan Semua Nilai
                        </button>
                    </div>
                    @endif
                </form>
            </div>
            @endif

        </div>
    </div>
</x-app-layout>