<x-admin-layout>

    {{-- Mensaje de éxito --}}
    @if (session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">
            {{ session('success') }}
        </div>
    @endif

    @php
        $totalPacientes = \App\Models\User::pacientes()->count();
        $totalTurnosMes = \App\Models\Turno::whereMonth('fecha_turno', now()->month)
            ->where('estado_turno', '!=', 'cancelado')
            ->count();
        $hoy = \Carbon\Carbon::today()->toDateString();
        $totalTurnosHoy = \App\Models\Turno::whereDate('fecha_turno', $hoy)
            ->where('estado_turno', '!=', 'cancelado')
            ->count();
    @endphp

    {{-- tarjetas --}}
    <section class="grid gap-4 grid-cols-1 sm:grid-cols-3">
        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
            <p class="text-xs font-semibold text-primary uppercase tracking-widest">Pacientes</p>
            <p class="mt-2 text-4xl font-bold text-gray-900">{{ $totalPacientes }}</p>
            <p class="text-sm text-gray-500">Activos registrados</p>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
            <p class="text-xs font-semibold text-primary uppercase tracking-widest">Turnos del mes</p>
            <p class="mt-2 text-4xl font-bold text-gray-900">{{ $totalTurnosMes }}</p>
            <p class="text-sm text-gray-500">Total en {{ now()->translatedFormat('F') }}</p>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
            <p class="text-xs font-semibold text-primary uppercase tracking-widest">Turnos de hoy</p>
            <p class="mt-2 text-4xl font-bold text-gray-900">{{ $totalTurnosHoy }}</p>
            <p class="text-sm text-gray-500">Programados para hoy</p>
        </div>
    </section>

    {{-- Livewire components (Agenda hoy + Próxima) --}}
    <section class="mt-8 grid gap-6 lg:grid-cols-2">
        <div>
            @livewire('dashboard.agenda-hoy')
        </div>

        <div>
            @livewire('dashboard.agenda-proxima')
        </div>
    </section>

    <section class="mt-8">
        @livewire('dashboard.calendario-disponibilidad')
    </section>

    <!-- {{-- Crear turno --}}
    <section class="mt-8">
        <div class="rounded-3xl border border-gray-200 bg-white p-6 shadow-sm flex justify-between items-center">
            <div>
                <p class="text-xs uppercase tracking-widest text-primaryDark">Crear</p>
                <h3 class="text-xl font-semibold text-gray-900">Crear Turno a Paciente</h3>
            </div>

            <a href="{{ route('turnos.create') }}"
               class="h-10 w-10 rounded-full bg-primary flex items-center justify-center text-white shadow hover:bg-primaryDark">
                +
            </a>
        </div>
    </section> -->
</x-admin-layout>
