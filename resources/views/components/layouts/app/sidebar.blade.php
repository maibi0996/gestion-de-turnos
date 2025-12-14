<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    @include('partials.head')
</head>
<body class="min-h-screen bg-white">

    <flux:sidebar sticky stashable class="border-e border-zinc-200 bg-zinc-50">

        <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

        @php
            $user = auth()->user();
            $isEmployee = $user?->hasRole('Profesional') ?? false;
        @endphp

        @if($isEmployee)
            <a href="{{ route('dashboard') }}"
               class="me-5 flex items-center space-x-2 rtl:space-x-reverse"
               wire:navigate>
                <x-app-logo />
            </a>
        @else
            <a href="{{ route('mis-turnos') }}"
               class="me-5 flex items-center space-x-2 rtl:space-x-reverse"
               wire:navigate>
                <x-app-logo />
            </a>
        @endif

        @if($isEmployee)
            <flux:navlist variant="outline" class="mt-4">

                <flux:navlist.group heading="Panel">

                    <flux:navlist.item
                        icon="home"
                        :href="route('dashboard')"
                        :current="request()->routeIs('dashboard')"
                        wire:navigate>
                        Dashboard
                    </flux:navlist.item>

                    {{-- <flux:navlist.item
                        icon="users"
                        :href="route('pacientes.index')"
                        :current="request()->routeIs('pacientes.*')"
                        wire:navigate>
                        Gestión de pacientes
                    </flux:navlist.item> --}}

                </flux:navlist.group>

            </flux:navlist>
        @endif

        <flux:spacer />
        <flux:dropdown class="hidden lg:block" position="bottom" align="start">

            <flux:profile
                :name="auth()->user()->name"
                :initials="strtoupper(substr(auth()->user()->name, 0, 1))"
                icon:trailing="chevrons-up-down"
            />

            <flux:menu class="w-[220px]">

                <div class="px-3 py-2 text-sm">
                    <strong>{{ auth()->user()->name }}</strong><br>
                    <span class="text-xs text-neutral-500">{{ auth()->user()->email }}</span>
                </div>

                <flux:menu.separator />

                <flux:menu.item
                    :href="route('profile')"
                    icon="cog"
                    wire:navigate>
                    Configuración
                </flux:menu.item>

                <flux:menu.separator />

                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                        Cerrar sesión
                    </flux:menu.item>
                </form>

            </flux:menu>

        </flux:dropdown>

    </flux:sidebar>

    <flux:header class="lg:hidden">

        <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

        <flux:spacer />

        <flux:dropdown position="top" align="end">
            <flux:profile
                :initials="strtoupper(substr(auth()->user()->name, 0, 1))"
                icon-trailing="chevron-down"
            />

            <flux:menu>

                <div class="px-3 py-2 text-sm">
                    <strong>{{ auth()->user()->name }}</strong><br>
                    <span class="text-xs text-neutral-500">{{ auth()->user()->email }}</span>
                </div>

                <flux:menu.separator />

                <flux:menu.item :href="route('profile')" icon="cog" wire:navigate>
                    Configuración
                </flux:menu.item>

                <flux:menu.separator />

                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                        Cerrar sesión
                    </flux:menu.item>
                </form>

            </flux:menu>

        </flux:dropdown>

    </flux:header>


    {{ $slot }}

    @fluxScripts
</body>
</html>
