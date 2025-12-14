@php
    $user = auth()->user();

    // Si es Super Admin o Profesional -> va al dashboard
    $homeRoute = $user && $user->hasRole('Super Admin', 'Profesional')
        ? 'dashboard'
        : 'welcome';
@endphp

<x-admin-layout>
    <x-slot name="header">
        <div class="max-w-4xl mx-auto mt-8 mb-10 px-4">
            <div class="flex items-end gap-8">

                <!-- INICIO -->
                <a href="{{ route($homeRoute) }}" 
                   class="flex flex-col items-center gap-1 text-primary hover:text-primaryDark transition">
                    
                    <div class="flex items-center gap-2 text-sm font-medium">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" 
                             viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M3 12l9-9 9 9M4 10v10a1 1 0 001 1h5m4 0h5a1 1 0 001-1V10" />
                        </svg>
                        Inicio
                    </div>

                    <span class="h-[2px] w-full bg-primary rounded-full"></span>
                </a>

                <div class="flex flex-col items-center gap-1">
                    <span class="text-lg font-bold text-gray-900">
                        Editar perfil
                    </span>
                    <span class="h-[2px] w-full bg-gray-300 rounded-full"></span>
                </div>

            </div>
        </div>
    </x-slot>

    <div class="flex justify-center px-4 py-10 bg-gradient-to-br from-[#EAF7F5] to-white min-h-screen">
        <div class="w-full max-w-4xl space-y-8">
            <div class="bg-white rounded-2xl shadow-xl p-8">
                <div class="max-w-xl">
                    <livewire:profile.update-profile-information-form />
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-xl p-8">
                <div class="max-w-xl">
                    <livewire:profile.update-password-form />
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-xl p-8">
                <div class="max-w-xl">
                    <livewire:profile.delete-user-form />
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
