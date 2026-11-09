@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('tasks.index') }}" class="text-gray-400 hover:text-gray-600 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
        </a>
        <h1 class="text-2xl font-bold text-gray-900">Buat Tugas Baru</h1>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-8">
        <form method="POST" action="{{ route('tasks.store') }}" id="task-form">
            @csrf

            <div class="space-y-6">
                <div>
                    <label for="title" class="block text-sm font-semibold text-gray-700 mb-1.5">Judul Tugas <span class="text-red-500">*</span></label>
                    <input
                        type="text"
                        id="title"
                        name="title"
                        value="{{ old('title') }}"
                        required
                        maxlength="255"
                        placeholder="Contoh: Latihan Soal Matematika Bab 5"
                        class="w-full px-4 py-2.5 border rounded-lg text-sm transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 {{ $errors->has('title') ? 'border-red-400 bg-red-50' : 'border-gray-300 focus:border-indigo-400' }}"
                    >
                    @error('title')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="class_name" class="block text-sm font-semibold text-gray-700 mb-1.5">Kelas <span class="text-red-500">*</span></label>
                    <input
                        type="text"
                        id="class_name"
                        name="class_name"
                        value="{{ old('class_name') }}"
                        required
                        maxlength="100"
                        placeholder="Contoh: Kelas 10A"
                        class="w-full px-4 py-2.5 border rounded-lg text-sm transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 {{ $errors->has('class_name') ? 'border-red-400 bg-red-50' : 'border-gray-300 focus:border-indigo-400' }}"
                    >
                    @error('class_name')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="due_date" class="block text-sm font-semibold text-gray-700 mb-1.5">Tenggat Waktu <span class="text-red-500">*</span></label>
                    <input
                        type="date"
                        id="due_date"
                        name="due_date"
                        value="{{ old('due_date') }}"
                        required
                        min="{{ date('Y-m-d') }}"
                        class="w-full px-4 py-2.5 border rounded-lg text-sm transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 {{ $errors->has('due_date') ? 'border-red-400 bg-red-50' : 'border-gray-300 focus:border-indigo-400' }}"
                    >
                    @error('due_date')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="description" class="block text-sm font-semibold text-gray-700 mb-1.5">Deskripsi</label>
                    <textarea
                        id="description"
                        name="description"
                        rows="5"
                        placeholder="Deskripsi tugas (opsional)..."
                        class="w-full px-4 py-2.5 border rounded-lg text-sm transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 resize-y {{ $errors->has('description') ? 'border-red-400 bg-red-50' : 'border-gray-300 focus:border-indigo-400' }}"
                    >{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex gap-3 mt-8">
                <button
                    type="submit"
                    id="submit-btn"
                    class="flex-1 bg-indigo-600 text-white py-2.5 px-6 rounded-lg font-semibold text-sm hover:bg-indigo-700 transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-60 cursor-pointer"
                >
                    <span id="btn-text">Simpan Tugas</span>
                    <span id="btn-loading" class="hidden">⏳ Menyimpan...</span>
                </button>
                <a href="{{ route('tasks.index') }}" class="px-6 py-2.5 bg-gray-100 text-gray-600 rounded-lg font-semibold text-sm hover:bg-gray-200 transition-colors text-center">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('task-form').addEventListener('submit', function() {
    const btn = document.getElementById('submit-btn');
    document.getElementById('btn-text').classList.add('hidden');
    document.getElementById('btn-loading').classList.remove('hidden');
    btn.disabled = true;
});
</script>
@endsection

