<div class="rounded-3xl border border-gray-200 bg-white p-6 shadow-sm">
    <div class="flex justify-between items-center mb-4">
        <div>
            <p class="text-xs font-semibold uppercase tracking-widest text-primaryDark">Turnos del día</p>
            <h3 class="text-xl font-semibold text-gray-900">Agenda de hoy</h3>
        </div>
        <!-- <a href="{{ route('turnos.create') }}"
            class="h-10 w-10 rounded-full bg-primary flex items-center justify-center text-white shadow hover:bg-primaryDark">
            +
        </a> -->
    </div>

    @if($turnos->isEmpty())
        <p class="text-gray-500 text-sm py-4">No hay turnos programados para hoy.</p>
    @else
        <div class="space-y-4">
            @foreach($turnos as $turno)
                <div class="p-4 rounded-2xl border border-gray-200 bg-gray-50 flex justify-between items-center">

                    <div class="space-y-1">
                        <p class="text-lg font-bold text-gray-900">
                            {{ substr($turno->hora_inicio_turno, 0, 5) }} hs
                        </p>

                        <p class="font-semibold text-gray-800">{{ $turno->paciente->name }}</p>

                        <p class="text-xs text-gray-600">{{ $turno->servicio->nombre_servicio }}</p>

                        <p class="text-xs text-gray-600">
                            {{ $turno->profesional->user->name }}
                        </p>

                        <p class="text-xs font-bold mt-1
                            @if($turno->estado_turno === 'Pendiente') text-yellow-600
                            @elseif($turno->estado_turno === 'Confirmado') text-green-600
                            @else text-gray-500 @endif">
                            {{ $turno->estado_turno }}
                        </p>
                    </div>

                    <div class="flex flex-col gap-2 items-end">

                        <a href="{{ route('turnos.edit', $turno->id) }}"
                            class="px-4 py-1.5 rounded-xl bg-primary text-white text-sm hover:bg-cyan-600 transition">
                            Editar
                        </a>

                        @if($turno->estado_turno === 'Pendiente')
                            <form action="{{ route('turnos.confirmar', $turno->id) }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="px-4 py-1.5 rounded-xl bg-primaryDark text-white text-sm hover:bg-blue-600 transition">
                                    Confirmar
                                </button>
                            </form>
                        @endif

                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
