@extends('layouts.app')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Dashboard Siswa</h1>
        <p class="mt-1 text-gray-500">Selamat datang, <span class="font-semibold text-gray-700">{{ auth()->user()->name }}</span>!</p>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="bg-indigo-100 p-3 rounded-lg">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Total Tugas</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ $totalTasks }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="bg-green-100 p-3 rounded-lg">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Tugas Selesai</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ $completedTasks }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="bg-orange-100 p-3 rounded-lg">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Belum Selesai</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ max(0, $totalTasks - $completedTasks) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Tasks -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <h2 class="font-semibold text-gray-900">Tugas Terbaru</h2>
            <a href="{{ route('tasks.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">Lihat semua →</a>
        </div>

        @if($recentTasks->isEmpty())
            <div class="py-12 text-center text-gray-400">
                <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                <p>Belum ada tugas.</p>
            </div>
        @else
            <ul class="divide-y divide-gray-100">
                @foreach($recentTasks as $task)
                    @php $isCompleted = $task->completions->isNotEmpty(); @endphp
                    <li class="flex items-center justify-between px-6 py-4 hover:bg-gray-50 transition-colors">
                        <div class="min-w-0 flex-1">
                            <a href="" class="font-medium text-gray-900 hover:text-indigo-600 truncate block {{ $isCompleted ? 'line-through text-gray-400' : '' }}">
                                {{ $task->title }}
                            </a>
                            <div class="flex items-center gap-3 mt-1 text-xs text-gray-400">
                                <span>{{ $task->class_name }}</span>
                                <span>•</span>
                                <span>Tenggat: {{ $task->due_date->format('d M Y') }}</span>
                            </div>
                        </div>
                        <span class="ml-4 shrink-0 px-2.5 py-1 rounded-full text-xs font-semibold {{ $isCompleted ? 'bg-green-100 text-green-700' : 'bg-orange-100 text-orange-700' }}">
                            {{ $isCompleted ? '✓ Selesai' : 'Belum' }}
                        </span>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</div>
@endsection

