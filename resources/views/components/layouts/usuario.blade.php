<!DOCTYPE html>
<html lang="es">
<head>
    @include('partials.head')
    @livewireStyles
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>

@livewireScripts
<body class="min-h-screen bg-[#f3f8fb] relative z-0">
    <div x-data="{ openSidebar: false }" class="min-h-screen bg-[#f2f8fb]">
        <div class="flex min-h-screen">

            <div 
                x-show="openSidebar"
                x-transition.opacity
                class="fixed inset-0 bg-black/40 z-30 lg:hidden"
                @click="openSidebar = false">
            </div>

            <aside class="fixed lg:static inset-y-0 left-0 z-50 w-72 bg-white border-r shadow-sm flex flex-col items-center py-8 px-4 transform transition-transform duration-300 -translate-x-full lg:translate-x-0" :class="{ 'translate-x-0': openSidebar }">                
                <div class="mb-12 text-center">
                    <img src="{{ asset('images/LogoKinesio.png') }}" class="h-20 mx-auto mb-3">
                </div>

                <nav class="w-full px-4 space-y-1">
                    <a href="{{ route('welcome') }}"
                    @click="openSidebar = false"
                    @class([
                        'w-full relative flex items-center px-4 py-3 font-semibold transition',
                        'text-primary' => request()->routeIs('welcome'),
                        'text-gray-600 hover:text-primary group' => !request()->routeIs('welcome'),
                    ])>
                    
                    Inicio

                    <span @class([
                        'absolute right-0 top-0 h-full w-1 bg-primary rounded-l-full transition',
                        'opacity-100' => request()->routeIs('welcome'),
                        'opacity-0 group-hover:opacity-100' => !request()->routeIs('welcome')
                    ])></span>
                    </a>

                    <a href="{{ route('mis-turnos') }}"
                    @click="openSidebar = false"
                    @class([
                        'w-full relative flex items-center px-4 py-3 font-semibold transition',
                        'text-primary' => request()->routeIs('mis-turnos'),
                        'text-gray-600 hover:text-primary group' => !request()->routeIs('mis-turnos'),
                    ])>

                    Mis turnos

                    <span @class([
                        'absolute right-0 top-0 h-full w-1 bg-primary rounded-l-full transition',
                        'opacity-100' => request()->routeIs('mis-turnos'),
                        'opacity-0 group-hover:opacity-100' => !request()->routeIs('mis-turnos')
                    ])></span>
                    </a>

                    <a href="{{ route('ayuda') }}"
                    @click="openSidebar = false"
                    @class([
                        'w-full relative flex items-center px-4 py-3 font-semibold transition',
                        'text-primary' => request()->routeIs('ayuda'),
                        'text-gray-600 hover:text-primary group' => !request()->routeIs('ayuda'),
                    ])>

                    Ayuda

                    <span @class([
                        'absolute right-0 top-0 h-full w-1 bg-primary rounded-l-full transition',
                        'opacity-100' => request()->routeIs('ayuda'),
                        'opacity-0 group-hover:opacity-100' => !request()->routeIs('ayuda')
                    ])></span>
                    </a>
                </nav>

                <div class="mt-auto w-full pt-10">
                    <div class="rounded-xl border bg-gray-50 p-4 text-center shadow-sm">
                        <p class="font-semibold text-gray-800">
                            {{ auth()->user()->name }}
                        </p>
                        <p class="text-xs text-gray-500 mb-3">
                            Paciente
                        </p>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="w-full text-sm text-primary font-semibold hover:underline">
                                Cerrar sesión
                            </button>
                        </form>
                    </div>
                </div>
            </aside>

            <!-- BOTÓN MOVIL -->
            <button 
                class="lg:hidden fixed top-4 left-4 z-50 bg-white rounded-xl shadow p-2"
                @click="openSidebar = true">

                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-primary" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            <main class="flex-1 min-h-screen bg-gradient-to-b from-sky-100 via-sky-50 to-white p-8">
                <!-- HEADER PACIENTE -->
                <div class="w-full max-w-6xl mx-auto flex justify-end mb-12 pr-12">
                    <div class="relative group">

                        <button class="flex items-center gap-3 bg-transparent px-4 py-2 rounded-xl hover:bg-gray-100 transition">
                            <div class="flex h-11 w-11 items-center justify-center rounded-full bg-primary text-white font-bold text-lg">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>

                            <div class="text-left leading-tight">
                                <p class="font-semibold text-gray-800">
                                    {{ auth()->user()->name }}
                                </p>
                                <p class="text-xs text-gray-500">
                                    Paciente
                                </p>
                            </div>

                            <svg class="ml-1 h-4 w-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m19 9-7 7-7-7"/>
                            </svg>
                        </button>

                        <div class="absolute right-0 mt-2 w-44 bg-white rounded-xl shadow-lg border border-gray-200 opacity-0 pointer-events-none group-hover:opacity-100 group-hover:pointer-events-auto transition z-50">

                            <a href="{{ route('profile') }}"
                            class="block px-4 py-3 text-sm font-medium text-gray-800 hover:bg-primary/10 transition rounded-b-xl">
                                Editar perfil
                            </a>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                class="w-full text-left px-4 py-3 text-sm font-medium text-primaryDark hover:bg-primary/10 transition rounded-b-xl">
                                    Cerrar sesión
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="w-full max-w-6xl mx-auto bg-white/80 rounded-3xl shadow-2xl p-10 backdrop-blur">
                    {{ $slot }}
                </div>
            </main>
        </div>
    </div>