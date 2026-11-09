@extends('layouts.app')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Dashboard Guru</h1>
            <p class="mt-1 text-gray-500">Selamat datang, <span class="font-semibold text-gray-700">{{ auth()->user()->name }}</span>!</p>
        </div>
        <a href="{{ route('tasks.create') }}" class="inline-flex items-center gap-2 bg-indigo-600 text-white px-5 py-2.5 rounded-lg font-semibold text-sm hover:bg-indigo-700 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Buat Tugas
        </a>
    </div>

    <!-- Stat Card -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
        <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="bg-indigo-100 p-3 rounded-lg">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Total Tugas Dibuat</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ $totalTasks }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="bg-green-100 p-3 rounded-lg">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Tugas Terbaru</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ $recentTasks->count() }}</p>
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
                <p>Belum ada tugas. <a href="{{ route('tasks.create') }}" class="text-indigo-600 hover:underline">Buat sekarang!</a></p>
            </div>
        @else
            <ul class="divide-y divide-gray-100">
                @foreach($recentTasks as $task)
                    <li class="flex items-center justify-between px-6 py-4 hover:bg-gray-50 transition-colors">
                        <div class="min-w-0 flex-1">
                            <a href="" class="font-medium text-gray-900 hover:text-indigo-600 truncate block">
                                {{ $task->title }}
                            </a>
                            <div class="flex items-center gap-3 mt-1 text-xs text-gray-400">
                                <span>{{ $task->class_name }}</span>
                                <span>•</span>
                                <span>Tenggat: {{ $task->due_date->format('d M Y') }}</span>
                                <span>•</span>
                                <span>{{ $task->completions->count() }} selesai</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 ml-4 shrink-0">
                            <a href="" class="text-xs text-gray-500 hover:text-indigo-600 px-2 py-1 rounded hover:bg-indigo-50 transition-colors">Edit</a>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</div>
@endsection

