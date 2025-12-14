@props(['title' => null])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('partials.head')
</head>

<body class="min-h-screen bg-gradient-to-b from-primaryDark/10 via-white to-white text-gray-800 font-sans antialiased">
    @php
        $user = auth()->user();
        $isEmployee = $user?->hasRole('Super Admin', 'Profesional') ?? false;
        $isSuperAdmin = $user?->hasRole('Super Admin') ?? false;
        $doctorName = $user?->name ? \Illuminate\Support\Str::of($user->name)->headline() : 'Profesional';
        $especialidades = $user?->profesional?->especialidades?->pluck('nombre_especialidad')->filter()->implode(', ');
        $especialidadTexto = $especialidades ?: 'Especialidad no asignada';
        $turnoHoy = now()->locale('es')->translatedFormat('l j \\d\\e F');

        $navItems = collect([
            [
                'key'    => 'dashboard',
                'title'  => 'Turnos',
                'label'  => 'Gestión de turnos',
                'route'  => route('dashboard'),
                'active' => request()->routeIs('dashboard'),
                'show'   => $isEmployee,
            ],
            [
                'key'    => 'pacientes',
                'title'  => 'Pacientes',
                'label'  => 'Gestión de pacientes',
                'route'  => route('pacientes.index'),
                'active' => request()->routeIs('pacientes.*'),
                'show'   => $isEmployee,
            ],
            // [
            //     'key'    => 'personal',
            //     'title'  => 'Personal',
            //     'label'  => 'Gestión de profesionales',
            //     'route'  => route('empleados.create'),
            //     'active' => request()->routeIs('empleados.*'),
            //     'show'   => $isSuperAdmin,
            // ],
            [
                'key'    => 'usuarios',
                'title'  => 'Perfil',
                'label'  => 'Editar Perfil',
                'route'  => route('profile'),
                'active' => request()->routeIs('profile'),
                'show'   => $isEmployee,
            ],
        ])->filter(fn ($item) => $item['show']);
    @endphp

    <div x-data="{ sidebarOpen: false }"  class="relative flex min-h-screen w-full overflow-x-hidden">
        <aside class="fixed inset-y-0 left-0 z-30 w-72 flex flex-col transform bg-[#0b2c4c] border-r border-gray-200 text-white
                transition-transform duration-300 ease-in-out
                lg:static lg:translate-x-0"
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            x-cloak>
            <div class="flex flex-col items-center justify-center px-5 py-8 gap-2">
                <x-application-logo class="h-14 w-14 text-blue-600" />

                <h1 class="text-xl font-bold uppercase tracking-[0.35em] text-white">
                    Panel
                </h1>

                {{-- <button class="rounded-lg border border-gray-300 bg-white p-2 text-primaryDark hover:bg-gray-100 lg:hidden" x-on:click="sidebarOpen = false">
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button> --}}
            </div>

            <nav class="space-y-2 px-4 overflow-y-auto">
                @foreach ($navItems as $item)
                    <a
                        href="{{ $item['route'] }}"
                        wire:navigate
                        class="group flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium transition
                        {{ $item['active']
                            ? 'bg-cyan-500/15 text-cyan-300 ring-1 ring-cyan-400/30 shadow-cyan-500/20 shadow'
                            : 'text-white/70 hover:text-cyan-300 hover:bg-cyan-400/10 transition' }}">

                        <span class="flex h-10 w-10 items-center justify-center rounded-lg text-white/70 group-hover:text-cyan-400 transition">
                            @switch($item['key'])
                                @case('dashboard')
                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 17.25v-4.5A2.25 2.25 0 0 1 6 10.5h12a2.25 2.25 0 0 1 2.25 2.25v4.5M3.75 17.25A2.25 2.25 0 0 0 6 19.5h12a2.25 2.25 0 0 0 2.25-2.25M3.75 17.25v-9A2.25 2.25 0 0 1 6 6h12a2.25 2.25 0 0 1 2.25 2.25v9"/>
                                    </svg>
                                @break

                                @case('pacientes')
                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 7.5a3 3 0 1 1 6 0m3.75 13.5a4.5 4.5 0 0 0-9 0M5.25 21a4.5 4.5 0 0 1 9 0"/>
                                    </svg>
                                @break

                                @case('usuarios')
                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15.75 6.75a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.5 20.035a8.25 8.25 0 1 1 15 0"/>
                                    </svg>
                                @break
                            @endswitch
                        </span>

                        <div class="leading-tight">
                            <p class="text-xs font-semibold uppercase tracking-wide text-white/50 group-hover:text-cyan-300">
                                {{ $item['title'] }}
                            </p>
                            <p class="text-sm font-semibold text-white group-hover:text-cyan-200">
                                {{ $item['label'] }}
                            </p>
                        </div>
                    </a>
                @endforeach
            </nav>

            <div class="mt-auto px-4 pb-8">
                <div class="rounded-xl border border-gray-200 bg-white p-4 text-sm text-white/70">
                    <p class="font-semibold text-gray-800">{{ $user?->name }}</p>
                    <p class="text-xs uppercase tracking-wide text-blue-600">{{ $user?->roleName() }}</p>

                    <form method="POST" action="{{ route('logout') }}" class="mt-4">
                        @csrf
                        <button
                            type="submit"
                            class="flex w-full items-center justify-center gap-2 rounded-lg border border-blue-200 bg-blue-50 px-3 py-2 text-sm font-semibold text-blue-700 hover:bg-blue-100 transition"
                        >
                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6A2.25 2.25 0 0 0 5.25 5.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15"/>
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 9l3 3m0 0-3 3m3-3H3"/>
                            </svg>
                            Cerrar sesión
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <div
            x-show="sidebarOpen"
            x-transition.opacity
            class="fixed inset-0 z-20 bg-black/50 lg:hidden"
            @click="sidebarOpen = false"
        ></div>

        <div class="flex flex-1 flex-col">
            <header class="border-b border-gray-200 bg-white">
                <div class="flex flex-col gap-4 px-4 py-5 sm:px-6 lg:flex-row lg:items-center lg:justify-between">
                    <div class="flex items-center gap-4">
                        <button
                            class="rounded-lg border border-gray-300 bg-white p-2 text-primaryDark hover:bg-gray-100 lg:hidden"
                            x-on:click="sidebarOpen = true"
                        >
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3.75 5.25h16.5M3.75 12h16.5m-16.5 6.75h16.5"/>
                            </svg>
                        </button>

                        <div class="flex flex-col">
                            <span class="text-sm uppercase tracking-[0.25em] text-blue-600">Bienvenida Doc</span>
                            <h1 class="text-2xl font-bold text-gray-900">{{ $doctorName }}</h1>
                            <p class="text-xs text-gray-500">{{ ucfirst($turnoHoy) }}</p>
                        </div>
                    </div>

                    <div class="flex w-full max-w-sm items-center gap-3 rounded-xl border border-gray-200 bg-white px-4 py-3 shadow-sm">
                        <div class="flex h-11 w-11 flex-shrink-0 items-center justify-center rounded-full bg-primary text-white font-bold text-lg">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>

                        <div class="min-w-0 leading-tight">
                            <p class="font-semibold text-gray-800 truncate"> {{ $user->name }} </p>
                            <p class="text-xs uppercase tracking-wide text-primary truncate"> {{ $especialidadTexto }}</p>
                        </div>
                    </div>
                </div>
            </header>

            <main class="min-h-screen bg-gradient-to-b from-sky-100 via-sky-50 to-white">
                <div class="px-4 py-6 sm:px-6 lg:px-10">
                    {{ $slot }}
                </div>
            </main>
        </div>
    </div>

    @fluxScripts
</body>
</html>
