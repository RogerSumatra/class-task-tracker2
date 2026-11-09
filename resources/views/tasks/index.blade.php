@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header & Filter -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Daftar Tugas</h1>
            <p class="mt-1 text-sm text-gray-500">{{ $tasks->total() }} tugas ditemukan</p>
        </div>
        @if(auth()->user()->isGuru())
            <a href="{{ route('tasks.create') }}" class="inline-flex items-center gap-2 bg-indigo-600 text-white px-5 py-2.5 rounded-lg font-semibold text-sm hover:bg-indigo-700 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Buat Tugas
            </a>
        @endif
    </div>

    <!-- Search & Filter Bar -->
    <form method="GET" action="{{ route('tasks.index') }}" class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm">
        <div class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"></path></svg>
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Cari judul tugas..."
                    class="w-full pl-9 pr-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-400"
                >
            </div>
            <select
                name="status"
                class="px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-400"
            >
                <option value="" {{ !request('status') ? 'selected' : '' }}>Semua Status</option>
                <option value="selesai" {{ request('status') === 'selesai' ? 'selected' : '' }}>✓ Selesai</option>
                <option value="belum" {{ request('status') === 'belum' ? 'selected' : '' }}>⏳ Belum Selesai</option>
            </select>
            <button type="submit" class="px-5 py-2.5 bg-indigo-600 text-white rounded-lg text-sm font-semibold hover:bg-indigo-700 transition-colors cursor-pointer">
                Filter
            </button>
            @if(request('search') || request('status'))
                <a href="{{ route('tasks.index') }}" class="px-5 py-2.5 bg-gray-100 text-gray-600 rounded-lg text-sm font-medium hover:bg-gray-200 transition-colors text-center">
                    Reset
                </a>
            @endif
        </div>
    </form>

    <!-- Task List -->
    @if($tasks->isEmpty())
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm py-16 text-center">
            <svg class="w-14 h-14 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
            <p class="text-gray-500 font-medium">Tidak ada tugas yang ditemukan.</p>
            @if(request('search') || request('status'))
                <p class="text-sm text-gray-400 mt-1">Coba ubah filter pencarian.</p>
            @endif
        </div>
    @else
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm divide-y divide-gray-100">
            @foreach($tasks as $task)
                @php
                    $isCompleted = $task->completions->isNotEmpty();
                    $isPastDue = $task->due_date->isPast() && !$isCompleted;
                @endphp
                <div class="flex items-start gap-4 px-6 py-5 hover:bg-gray-50 transition-colors group">
                    @if(auth()->user()->isSiswa())
                        <form method="POST" action="{{ route('tasks.toggle-complete', $task) }}" class="mt-0.5 shrink-0">
                            @csrf
                            <button
                                type="submit"
                                title="{{ $isCompleted ? 'Batalkan selesai' : 'Tandai selesai' }}"
                                class="w-6 h-6 rounded-full border-2 flex items-center justify-center transition-colors cursor-pointer {{ $isCompleted ? 'bg-green-500 border-green-500 text-white' : 'border-gray-300 hover:border-indigo-400 text-transparent hover:text-indigo-400' }}"
                            >
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            </button>
                        </form>
                    @endif

                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between gap-4">
                            <div class="min-w-0 flex-1">
                                <a href="{{ route('tasks.show', $task) }}" class="font-semibold text-gray-900 hover:text-indigo-600 transition-colors {{ $isCompleted ? 'line-through text-gray-400' : '' }}">
                                    {{ $task->title }}
                                </a>
                                @if($task->description)
                                    <p class="mt-1 text-sm text-gray-500 line-clamp-2">{{ $task->description }}</p>
                                @endif
                                <div class="flex flex-wrap items-center gap-3 mt-2 text-xs text-gray-400">
                                    <span class="flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                        {{ $task->class_name }}
                                    </span>
                                    <span>•</span>
                                    <span class="flex items-center gap-1 {{ $isPastDue ? 'text-red-500 font-medium' : '' }}">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        {{ $task->due_date->format('d M Y') }}
                                        @if($isPastDue) <span class="text-red-500">(Terlambat)</span> @endif
                                    </span>
                                    <span>•</span>
                                    <span>oleh {{ $task->creator->name }}</span>
                                </div>
                            </div>

                            <div class="flex items-center gap-2 shrink-0">
                                @if(auth()->user()->isSiswa())
                                    @if($isCompleted)
                                    <span class="px-2.5 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded-full">✓ Selesai</span>
                                    @elseif($isPastDue)
                                        <span class="px-2.5 py-1 bg-red-100 text-red-700 text-xs font-semibold rounded-full">⚠ Terlambat</span>
                                    @else
                                        <span class="px-2.5 py-1 bg-orange-100 text-orange-700 text-xs font-semibold rounded-full">⏳ Belum</span>
                                    @endif
                                @endif

                                @if(auth()->user()->isGuru())
                                    <a href="" class="text-xs text-gray-400 hover:text-indigo-600 px-2 py-1 rounded hover:bg-indigo-50 transition-colors">Edit</a>
                                    <form method="" action="" onsubmit="return confirm('Yakin hapus tugas ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs text-gray-400 hover:text-red-600 px-2 py-1 rounded hover:bg-red-50 transition-colors cursor-pointer">Hapus</button>
                                    </form>
                                @endif
                                
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($tasks->hasPages())
            <div class="flex justify-center">
                {{ $tasks->links() }}
            </div>
        @endif
    @endif
</div>
@endsection

