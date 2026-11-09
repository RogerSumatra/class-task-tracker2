@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <!-- Back button -->
    <div class="flex items-center gap-4">
        <a href="{{ route('tasks.index') }}" class="text-gray-400 hover:text-gray-600 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
        </a>
        <h1 class="text-2xl font-bold text-gray-900 truncate">{{ $task->title }}</h1>
    </div>

    <!-- Task Detail Card -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-8 space-y-6">
        <!-- Status Badge & Actions -->
        <div class="flex items-start justify-between gap-4 flex-wrap">
            <div class="flex flex-wrap gap-2">
                @php $isPastDue = $task->due_date->isPast() && !$isCompleted; @endphp
                @if(auth()->user()->isSiswa())
                    @if($isCompleted)
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-green-100 text-green-700 text-sm font-semibold rounded-full">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                            Selesai
                        </span>
                    @elseif($isPastDue)
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-red-100 text-red-700 text-sm font-semibold rounded-full">
                            ⚠ Terlambat
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-orange-100 text-orange-700 text-sm font-semibold rounded-full">
                            ⏳ Belum Selesai
                        </span>
                    @endif
                @endif
                <span class="inline-flex items-center px-3 py-1.5 bg-indigo-100 text-indigo-700 text-sm font-semibold rounded-full">
                    {{ $task->class_name }}
                </span>
            </div>

            <!-- Actions for guru -->
            @if(auth()->user()->isGuru())
                <div class="flex items-center gap-2">
                    <a href="{{ route('tasks.edit', $task) }}" class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        Edit
                    </a>
                    <form method="POST" action="{{ route('tasks.destroy', $task) }}" onsubmit="return confirm('Yakin ingin menghapus tugas ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-medium text-red-600 bg-red-50 rounded-lg hover:bg-red-100 transition-colors cursor-pointer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            Hapus
                        </button>
                    </form>
                </div>
            @endif
        </div>

        <!-- Task Info -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 py-6 border-t border-b border-gray-100">
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Tenggat Waktu</p>
                <p class="mt-1.5 text-sm font-semibold text-gray-900 {{ $isPastDue ? 'text-red-600' : '' }}">
                    {{ $task->due_date->format('d F Y') }}
                    @if($isPastDue)
                        <span class="text-red-500 font-normal">(Terlambat)</span>
                    @endif
                </p>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Dibuat Oleh</p>
                <p class="mt-1.5 text-sm font-semibold text-gray-900">{{ $task->creator->name }}</p>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Tanggal Dibuat</p>
                <p class="mt-1.5 text-sm text-gray-700">{{ $task->created_at->format('d M Y, H:i') }}</p>
            </div>
            <div>
                @if(auth()->user()->isGuru())
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Jumlah Selesai</p>
                    <p class="mt-1.5 text-sm text-gray-700">{{ $task->completions->count() }} siswa</p>
                @endif
            </div>
        </div>

        <!-- Description -->
        @if($task->description)
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Deskripsi</p>
                <div class="text-sm text-gray-700 leading-relaxed whitespace-pre-wrap">{{ $task->description }}</div>
            </div>
        @endif

        <!-- Toggle completion for siswa -->
        @if(auth()->user()->isSiswa())
            <div class="pt-4 border-t border-gray-100">
                <form method="POST" action="{{ route('tasks.toggle-complete', $task) }}" id="complete-form">
                    @csrf
                    <button
                        type="submit"
                        id="complete-btn"
                        class="inline-flex items-center gap-2 px-6 py-3 rounded-lg font-semibold text-sm transition-colors cursor-pointer focus:outline-none focus:ring-2 focus:ring-offset-2 {{ $isCompleted ? 'bg-gray-100 text-gray-700 hover:bg-gray-200 focus:ring-gray-400' : 'bg-green-600 text-white hover:bg-green-700 focus:ring-green-500' }}"
                    >
                        @if($isCompleted)
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            <span id="complete-text">Batalkan Selesai</span>
                        @else
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            <span id="complete-text">Tandai Selesai</span>
                        @endif
                        <span id="complete-loading" class="hidden">⏳ Memproses...</span>
                    </button>
                </form>
            </div>
        @endif
    </div>

    <!-- Completion list for guru -->
    @if(auth()->user()->isGuru() && $task->completions->isNotEmpty())
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
            <h2 class="font-semibold text-gray-900 mb-4">Siswa yang Sudah Menyelesaikan ({{ $task->completions->count() }})</h2>
            <ul class="divide-y divide-gray-100">
                @foreach($task->completions as $completion)
                    <li class="flex items-center justify-between py-3">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-semibold text-sm">
                                {{ substr($completion->user->name, 0, 1) }}
                            </div>
                            <span class="text-sm font-medium text-gray-900">{{ $completion->user->name }}</span>
                        </div>
                        <span class="text-xs text-gray-400">{{ $completion->completed_at->format('d M Y, H:i') }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif
</div>

<script>
document.getElementById('complete-form')?.addEventListener('submit', function() {
    const btn = document.getElementById('complete-btn');
    document.getElementById('complete-text').classList.add('hidden');
    document.getElementById('complete-loading').classList.remove('hidden');
    btn.disabled = true;
});
</script>
@endsection

