<div>
    {{-- TÍTULO --}}
    @php
        $nombreMes = \Carbon\Carbon::createFromDate($anio, $mes, 1)
            ->locale('es')
            ->translatedFormat('F Y');
    @endphp

    <div class="mb-4">
        <p class="text-xs uppercase tracking-widest text-primaryDark">Calendario</p>
        <h3 class="text-xl font-semibold text-gray-900">
            {{ ucfirst($nombreMes) }}
        </h3>
    </div>

    {{-- DÍAS DE LA SEMANA --}}
    <div class="grid grid-cols-7 gap-2 text-center text-xs font-medium text-gray-500 mb-3">
        @foreach(['Lun','Mar','Mié','Jue','Vie','Sáb','Dom'] as $dia)
            <div>{{ $dia }}</div>
        @endforeach
    </div>

    {{-- CALENDARIO --}}
    <div class="grid grid-cols-7 gap-2">
        @php $fecha = $inicio->copy(); @endphp

        @while($fecha <= $fin)
            @php
                $fechaStr = $fecha->toDateString();
                $diaTexto = ucfirst($fecha->locale('es')->dayName);

                $habilitado   = in_array($diaTexto, $diasHabilitados);
                $ocupadoDia   = in_array($fechaStr, $diasConTurnos);
                $esMes        = $fecha->month === $mes;
                $seleccionado = $fechaSeleccionada === $fechaStr;
                $tieneTooltip = $ocupadoDia && isset($pacientesPorDia[$fechaStr]);
            @endphp

            <div x-data="{ open: false }" class="relative">
                <button
                    @if($habilitado)
                        wire:click="seleccionarDia('{{ $fechaStr }}')"
                    @endif
                    @mouseenter="open = true"
                    @mouseleave="open = false"
                    class="
                        w-full py-3 text-center text-sm transition
                        rounded-md
                        {{ !$esMes ? 'opacity-30' : '' }}

                        {{ !$habilitado
                            ? 'bg-gray-100 text-gray-400 cursor-default'
                            : ($ocupadoDia
                                ? 'bg-[#007ee5]/20 text-[#007ee5]'
                                : 'bg-[#06c4a0]/20 text-[#06c4a0] hover:bg-[#06c4a0]/30')
                        }}

                        {{ $seleccionado ? 'ring-2 ring-offset-1 ring-[#007ee5]/40' : '' }}
                    ">
                    {{ $fecha->day }}
                </button>

                @if($tieneTooltip)
                    <div
                        x-show="open"
                        x-transition
                        class="absolute z-50 bottom-full mb-2 w-44
                               text-xs bg-white shadow-lg border rounded-lg p-2">
                        <p class="font-semibold text-gray-700 mb-1">Turnos:</p>
                        @foreach($pacientesPorDia[$fechaStr] as $t)
                            <p class="text-gray-500">
                                {{ $t['hora'] }} · {{ $t['nombre'] }}
                            </p>
                        @endforeach
                    </div>
                @endif
            </div>

            @php $fecha->addDay(); @endphp
        @endwhile
    </div>

    {{-- categorias --}}
    <div class="mt-4 flex flex-wrap items-center gap-6 text-xs text-gray-500">
        <div class="flex items-center gap-2">
            <span class="h-3 w-3 rounded-full bg-[#06c4a0]"></span>
            <span>Disponible</span>
        </div>

        <div class="flex items-center gap-2">
            <span class="h-3 w-3 rounded-full bg-[#007ee5]"></span>
            <span>Ocupado</span>
        </div>

        <div class="flex items-center gap-2">
            <span class="h-3 w-3 rounded-full bg-gray-300"></span>
            <span>Día no laboral</span>
        </div>
    </div>

    @if($mensajeDia)
        <div class="mt-5 text-sm font-medium text-gray-600">
            {{ $mensajeDia }}
        </div>
    @endif

</div>