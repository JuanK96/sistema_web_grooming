<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Caprichos Pet Grooming</title>
    <link rel="icon" type="image/png" href="{{ asset('img/Isotipo_Caprichos_Rojo.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Styles -->
    @livewireStyles
</head>

<body class="font-sans antialiased">
    <x-banner />
    <div
        style="background-image: url('{{ asset('img/PET.png') }}'); background-size: cover; background-position: center;">
        <div class="min-h-screen flex flex-col lg:grid lg:grid-cols-6 lg:grid-rows-8">
            <!-- Botón de menú móvil -->
            <div class="lg:hidden flex justify-between items-center p-4 bg-[#44b549] text-white">
                <div class=" shrink-0 flex justify-center   ">
                    <a href="{{ route('dashboard') }}">
                        <x-application-mark class="block h-auto w-30" />
                    </a>
                </div>
                <button id="mobile-menu-toggle" class="focus:outline-none">
                    <!-- Ícono hamburguesa -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
            <!-- Sidebar -->
            <div id="sidebar"
                class="lg:row-span-8 lg:block hidden fixed lg:static top-0 left-0 w-64 lg:w-auto h-full lg:h-auto bg-white z-50 shadow-lg lg:shadow-none">
                @livewire('navigation-menu')
            </div>
            <!-- Header -->
            <div class="lg:col-span-5 row-span-1 flex flex-row h-20 lg:text-xl text-s justify-between items-center px-5 bg-[#2e3192]">
                @if (isset($header))
                    <header class="text-white">
                        {{ $header }}
                    </header>
                @endif
                <!-- Menú de usuario -->
                <div class="flex items-center ms-3">
                    <div class="ms-3 relative">
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <span class="inline-flex rounded-md">
                                    <button type="button"
                                        class="inline-flex items-center px-3 py-2 border border-transparent lg:text-sm text-xs leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition">
                                        {{ explode(' ', Auth::user()->name)[0] }}
                                        <svg class="ms-2 -me-0.5 size-4" xmlns="http://www.w3.org/2000/svg"
                                            fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                        </svg>
                                    </button>
                                </span>
                            </x-slot>
                            <x-slot name="content">
                                <div class="block px-4 py-2 text-xs text-gray-400">
                                    {{ __('Manage Account') }}
                                </div>
                                <x-dropdown-link href="{{ route('profile.show') }}">
                                    {{ __('Profile') }}
                                </x-dropdown-link>
                                <div class="border-t border-gray-200"></div>
                                <form method="POST" action="{{ route('logout') }}" x-data>
                                    @csrf
                                    <x-dropdown-link href="{{ route('logout') }}" @click.prevent="$root.submit();">
                                        {{ __('Log Out') }}
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    </div>
                </div>
            </div>
            <!-- Contenido principal -->
            <div class="lg:col-span-5 lg:row-span-7">
                <main>
                    {{ $slot }}
                </main>
            </div>
        </div>
    </div>
    @stack('modals')
    @livewireScripts
    <!-- Script para menú móvil -->
    <script>
        const toggleBtn = document.getElementById('mobile-menu-toggle');
        const sidebar = document.getElementById('sidebar');
        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('hidden');
        });
    </script>
</body>

</html>
