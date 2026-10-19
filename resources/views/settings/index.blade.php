<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Pengaturan Identitas Raport
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('settings.update') }}">
                    @csrf
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Nama Kepala Madrasah</label>
                        <input type="text" name="headmaster_name" value="{{ old('headmaster_name', $headmasterName) }}" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">NIP Kepala Madrasah</label>
                        <input type="text" name="headmaster_nip" value="{{ old('headmaster_nip', $headmasterNip) }}" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700">Tanggal Titimangsa Cetak Raport</label>
                        <input type="text" name="report_date" value="{{ old('report_date', $reportDate) }}" 
                               placeholder="Contoh: Purworejo, 20 Desember 2025"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" style="background-color: #16a34a; color: #ffffff;" class="px-6 py-2 bg-indigo-600 text-white font-bold rounded-md shadow-sm hover:bg-indigo-700">
                            💾 Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>