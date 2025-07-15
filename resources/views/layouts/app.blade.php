<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'ScoreStream Pro') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .bg-gradient { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .card { @apply bg-white rounded-lg shadow-md p-6; }
        .btn-primary { @apply bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded; }
        .btn-success { @apply bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded; }
        .btn-danger { @apply bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded; }
        .btn-warning { @apply bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded; }
    </style>
</head>
<body class="bg-gray-100">
    <nav class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ route('dashboard') }}" class="text-xl font-bold text-gray-800">
                        ⚽ ScoreStream Pro
                    </a>
                </div>
                
                @auth
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-600">
                        ক্রেডিট: <span class="font-semibold text-green-600">{{ auth()->user()->credits_balance }}</span>
                    </span>
                    
                    <div class="flex space-x-2">
                        <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-gray-900">ড্যাশবোর্ড</a>
                        <a href="{{ route('matches.index') }}" class="text-gray-700 hover:text-gray-900">ম্যাচ</a>
                        <a href="{{ route('credits.purchase') }}" class="text-gray-700 hover:text-gray-900">ক্রেডিট কিনুন</a>
                        
                        @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="text-red-600 hover:text-red-800">অ্যাডমিন</a>
                        @endif
                    </div>
                    
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-gray-700 hover:text-gray-900">লগআউট</button>
                    </form>
                </div>
                @endauth
            </div>
        </div>
    </nav>

    <main class="py-8">
        @if(session('success'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-4">
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
        </div>
        @endif

        @if(session('error'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-4">
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                {{ session('error') }}
            </div>
        </div>
        @endif

        {{ $slot }}
    </main>
</body>
</html>