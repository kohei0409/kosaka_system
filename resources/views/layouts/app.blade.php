<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="https://www.kosaka.co.jp/system/resources/css/layout_style.css">
    <style>
        body {
            color: white;
        }
    </style>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
<div class="min-h-screen bg-gray-100 dark:bg-gray-900 flex">
    <!-- Sidebar -->
    @if (Auth::check() && in_array(Auth::user()->role->name, ['SuperAdmin', 'Admin', 'Manager']))
    <aside class="w-64 bg-gray-800 text-white flex-shrink-0">
        <div class="p-4">
            <h2 class="text-lg font-semibold">ユーザ管理</h2>
            <ul class="mt-4 space-y-2">
                  @if (in_array(Auth::user()->role->name, ['SuperAdmin', 'Admin']))
                <li>
                    <a href="{{ route('users.index') }}" class="block py-2 px-4 hover:bg-gray-700 rounded">
                        ユーザー一覧
                    </a>
                </li>

                <li>
                    <a href="{{ route('users.create') }}" class="block py-2 px-4 hover:bg-gray-700 rounded">
                        ユーザー登録
                    </a>
                </li>
                @endif

            </ul>
        </div>
    </aside>
    @endif


    <!-- Main Content -->
    <div class="flex-1">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @if (isset($header))
        <header class="bg-white dark:bg-gray-800 shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                {{ $header }}
            </div>
        </header>
        @endif

        <!-- Page Content -->
        <main class="p-6">
            @yield('content')
        </main>
    </div>
</div>
</body>
</html>
