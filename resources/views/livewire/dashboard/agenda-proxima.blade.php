<div class="rounded-3xl border border-gray-200 bg-white p-6 shadow-sm">
    <p class="text-xs font-semibold uppercase tracking-widest text-primaryDark">Próximos turnos</p>
    <h3 class="text-xl font-semibold text-gray-900">Agenda próxima</h3>

    @if($turnos->isEmpty())
        <p class="text-gray-500 text-sm py-4">No hay próximos turnos registrados.</p>
    @else
        <div class="mt-4 space-y-3">
            @foreach($turnos as $turno)
                <div class="p-4 rounded-xl border border-gray-200 bg-gray-50 flex justify-between items-center">
                    <div>
                        <p class="font-semibold text-gray-800">
                            {{ \Carbon\Carbon::parse($turno->fecha_turno)->translatedFormat('d M') }}
                            — {{ substr($turno->hora_inicio_turno, 0, 5) }} hs
                        </p>

                        <p class="text-sm text-gray-600">{{ $turno->paciente->name }}</p>

                        <p class="text-xs text-primaryDark font-semibold">{{ $turno->servicio->nombre_servicio }}</p>

                        <p class="text-xs text-gray-700">
                            {{ $turno->profesional->user->name }}
                        </p>

                        <p class="text-xs mt-1 font-bold
                            @if($turno->estado_turno === 'Pendiente') text-yellow-600
                            @elseif($turno->estado_turno === 'Confirmado') text-green-600
                            @else text-gray-500 @endif">
                            {{ $turno->estado_turno }}
                        </p>
                    </div>

                    <div class="flex gap-2">
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
