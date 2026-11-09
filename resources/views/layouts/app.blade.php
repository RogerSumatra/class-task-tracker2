<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Class Task Tracker') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Navbar -->
    <nav class="bg-indigo-700 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center gap-6">
                    <a href="{{ route('dashboard') }}" class="text-xl font-bold tracking-tight hover:text-indigo-200 transition-colors">
                        📚 Class Task Tracker
                    </a>
                    @auth
                        <div class="hidden sm:flex items-center gap-4 text-sm">
                            <a href="{{ route('dashboard') }}" class="hover:text-indigo-200 transition-colors {{ request()->routeIs('dashboard') ? 'text-white font-semibold underline underline-offset-4' : '' }}">
                                Dashboard
                            </a>
                            <a href="{{ route('tasks.index') }}" class="hover:text-indigo-200 transition-colors {{ request()->routeIs('tasks.index') ? 'text-white font-semibold underline underline-offset-4' : '' }}">
                                Tugas
                            </a>
                            @if(auth()->user()->isGuru())
                                <a href="{{ route('tasks.create') }}" class="hover:text-indigo-200 transition-colors {{ request()->routeIs('tasks.create') ? 'text-white font-semibold underline underline-offset-4' : '' }}">
                                    Buat Tugas
                                </a>
                            @endif
                        </div>
                    @endauth
                </div>
                <div class="flex items-center gap-4">
                    @auth
                        <div class="text-sm text-indigo-200">
                            <span class="font-medium text-white">{{ auth()->user()->name }}</span>
                            <span class="ml-1 px-2 py-0.5 rounded-full text-xs font-semibold {{ auth()->user()->isGuru() ? 'bg-yellow-400 text-yellow-900' : 'bg-green-400 text-green-900' }}">
                                {{ auth()->user()->role->label() }}
                            </span>
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-sm text-indigo-200 hover:text-white transition-colors cursor-pointer">
                                Keluar
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-sm hover:text-indigo-200 transition-colors">Masuk</a>
                        <a href="{{ route('register') }}" class="text-sm bg-white text-indigo-700 px-3 py-1.5 rounded-lg font-semibold hover:bg-indigo-50 transition-colors">Daftar</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
        @if(session('success'))
            <div class="flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg" role="alert">
                <svg class="w-5 h-5 text-green-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                <span class="text-sm font-medium">{{ session('success') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="flex items-start gap-3 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg" role="alert">
                <svg class="w-5 h-5 text-red-500 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                <ul class="text-sm space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <!-- Page Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="border-t border-gray-200 mt-16 py-6 text-center text-sm text-gray-400">
        Class Task Tracker &copy; {{ date('Y') }}
    </footer>
</body>
</html>

