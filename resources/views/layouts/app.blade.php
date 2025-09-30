<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ isset($title) ? $title . ' - ' : '' }}SafePoint Admin</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gray-50">
    <nav class="bg-white shadow">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ route('dashboard') }}" class="text-xl font-bold text-red-600">
                        SafePoint Admin
                    </a>
                </div>

                <div class="flex items-center space-x-4">
                    <div class="hidden md:flex space-x-8">
                        <a href="{{ route('dashboard') }}"
                            class="text-gray-500 hover:text-red-600 px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('dashboard') ? 'text-red-600 bg-red-50' : '' }}">
                            Dashboard
                        </a>
                        <a href="{{ route('cases.index') }}"
                            class="text-gray-500 hover:text-red-600 px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('cases.*') ? 'text-red-600 bg-red-50' : '' }}">
                            Kasus
                        </a>
                        @if (auth()->check() && auth()->user()->role === 'SUPERADMIN')
                            <a href="{{ route('users.index') }}"
                                class="text-gray-500 hover:text-red-600 px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('users.*') ? 'text-red-600 bg-red-50' : '' }}">
                                Users
                            </a>
                        @endif
                    </div>

                    @auth
                        <div class="flex items-center space-x-3">
                            <span class="text-sm text-gray-700">{{ auth()->user()->name }}</span>
                            <span
                                class="px-2 py-1 text-xs bg-red-100 text-red-800 rounded-full">{{ auth()->user()->role }}</span>
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="text-gray-500 hover:text-red-600 text-sm font-medium">
                                    Logout
                                </button>
                            </form>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-6xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        @if (session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>
</body>

</html>
