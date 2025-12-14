<div class="max-w-5xl mx-auto py-10 animate-fade-slide">

    <h1 class="text-center font-title text-2xl font-bold text-primaryDark mb-1">
        Mis turnos
    </h1>

    <p class="text-center text-sm text-gray-500 mb-8">
        Puedes ver tu lista de Turnos
    </p>

    {{-- FILTROS --}}
    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <div class="flex flex-wrap items-center gap-3">
            {{-- filtro mes --}}
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open"
                        class="flex items-center justify-between gap-2 bg-gray-100 rounded-lg px-4 py-2 text-sm font-semibold text-gray-800 min-w-[170px] hover:bg-gray-200 transition">
                    <span>
                        {{ $filtroMes ? \Carbon\Carbon::create()->month($filtroMes)->translatedFormat('F') : 'Todos los meses' }}
                    </span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                <div x-show="open" @click.away="open = false" x-transition
                     class="absolute z-50 mt-2 w-full bg-white rounded-xl shadow border overflow-hidden">
                    <button wire:click="$set('filtroMes','')" @click="open = false" class="w-full text-left px-4 py-2 text-sm hover:bg-primary/10">Todos los meses</button>
                    @foreach(range(1,12) as $mes)
                        <button wire:click="$set('filtroMes', {{ $mes }})" @click="open = false" class="w-full text-left px-4 py-2 text-sm hover:bg-primary/10">
                            {{ \Carbon\Carbon::create()->month($mes)->translatedFormat('F') }}
                        </button>
                    @endforeach
                </div>
            </div>

            {{-- filtro estado --}}
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" class="flex items-center justify-between gap-2 bg-gray-100 rounded-lg px-4 py-2 text-sm font-semibold min-w-[190px] hover:bg-gray-200 transition">
                    <span>{{ $filtroEstado ?: 'Todos los estados' }}</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                <div x-show="open" @click.away="open = false" x-transition class="absolute z-50 mt-2 w-full bg-white rounded-xl shadow border overflow-hidden">
                    <button wire:click="$set('filtroEstado','')" @click="open = false" class="w-full text-left px-4 py-2 text-sm hover:bg-primary/10">Todos los estados</button>
                    <button wire:click="$set('filtroEstado','Pendiente')" @click="open = false" class="w-full text-left px-4 py-2 text-sm hover:bg-yellow-50">Pendiente</button>
                    <button wire:click="$set('filtroEstado','Confirmado')" @click="open = false" class="w-full text-left px-4 py-2 text-sm hover:bg-emerald-50">Confirmado</button>
                    <button wire:click="$set('filtroEstado','Cancelado')" @click="open = false" class="w-full text-left px-4 py-2 text-sm hover:bg-red-50">Cancelado</button>
                </div>
            </div>
        </div>

        <div class="flex flex-wrap gap-2">
            @if($filtroMes)
                <span class="inline-flex items-center gap-1 bg-blue-50 text-primary border border-primary-200 text-xs font-semibold px-3 py-1 rounded-full">
                    {{ \Carbon\Carbon::create()->month((int)$filtroMes)->translatedFormat('F') }}
                    <button wire:click="$set('filtroMes','')">✕</button>
                </span>
            @endif

            @if($filtroEstado)
                <span class="inline-flex items-center gap-1 bg-blue-50 text-primary border border-primary-200 text-xs font-semibold px-3 py-1 rounded-full">
                    {{ $filtroEstado }}
                    <button wire:click="$set('filtroEstado','')">✕</button>
                </span>
            @endif
        </div>
    </div>

    {{-- DÍAS --}}
    <div class="bg-white rounded-2xl shadow border p-5 mb-8">
        <div class="grid grid-cols-7 gap-3 text-center text-xs font-semibold text-gray-500">
            @foreach(\Carbon\Carbon::now()->startOfWeek()->daysUntil(\Carbon\Carbon::now()->endOfWeek()) as $day)
                <div class="rounded-xl py-2 {{ $day->isToday() ? 'bg-primary text-white' : 'bg-gray-100 text-gray-600' }}">
                    <div>{{ strtoupper($day->isoFormat('ddd')) }}</div>
                    <div class="text-sm font-bold">{{ $day->format('d') }}</div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- LISTADO --}}
    @if($turnos->isEmpty())
        <div class="bg-white border shadow rounded-2xl p-10 text-center">
            <p class="text-gray-600 mb-2">Todavía no tenés turnos cargados.</p>
            <p class="text-sm text-gray-400">Reservá tu primer turno desde inicio.</p>
        </div>
    @else
        <div class="relative pl-6 space-y-6">
            <div class="absolute left-2 top-0 bottom-0 w-0.5 bg-gray-200"></div>

            @foreach($turnos as $turno)
                @php
                    $fechaHora = \Carbon\Carbon::parse($turno->fecha_turno.' '.$turno->hora_inicio_turno);
                    $estado = strtolower(trim($turno->estado_turno ?? ''));
                    $bg   = 'bg-white';
                    $dot  = 'bg-gray-300';
                    $text = 'text-primaryDark';
                    $chip = 'bg-gray-100 text-gray-600';

                    if ($estado === 'confirmado') {
                        $dot  = 'bg-emerald-500';
                        $chip = 'bg-emerald-100 text-emerald-700';
                    } elseif ($estado === 'pendiente') {
                        $dot  = 'bg-yellow-400';
                        $chip = 'bg-yellow-100 text-yellow-700';
                    } elseif ($estado === 'cancelado') {
                        $dot  = 'bg-red-400';
                        $chip = 'bg-red-100 text-red-700 opacity-70';
                    } elseif ($fechaHora->isPast()) {
                        $dot  = 'bg-gray-400';
                        $chip = 'bg-gray-200 text-gray-500';
                    }
                @endphp

                <div class="relative flex items-start gap-4">
                    <div class="w-4 h-4 rounded-full {{ $dot }} mt-2"></div>

                    <div class="w-full {{ $bg }} border rounded-2xl p-5 shadow flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <p class="text-sm font-bold {{ $text }}">
                                {{ $fechaHora->format('d/m/Y') }} — {{ substr($turno->hora_inicio_turno,0,5) }} hs
                            </p>
                            <p class="text-sm text-gray-600">{{ $turno->servicio->nombre_servicio ?? '—' }}</p>
                            <p class="text-xs text-gray-500">Prof: {{ $turno->profesional->user->name ?? '—' }}</p>

                            <span class="inline-block mt-2 text-xs font-semibold px-3 py-1 rounded-full {{ $chip }}">
                                {{ ucfirst($estado) }}
                            </span>
                        </div>

                        {{-- ACCIONES --}}
                        @if($estado !== 'cancelado')
                            <div class="flex flex-col gap-2 w-36">
                                <button wire:click="editar({{ $turno->id }})" class="px-3 py-2 rounded-xl border text-xs text-gray-700 hover:bg-gray-100">Editar</button>

                                {{-- El botón aparece sólo si estado es pendiente --}}
                                @if($estado === 'pendiente')
                                    <button wire:click="confirmar({{ $turno->id }})" class="px-3 py-2 rounded-xl border text-xs text-green-700 border-green-300 hover:bg-green-50">Confirmar turno</button>
                                @endif

                                <button wire:click="cancelar({{ $turno->id }})" class="px-3 py-2 rounded-xl border text-xs text-red-700 border-red-300 hover:bg-red-50">Cancelar</button>
                            </div>
                        @endif
                    </div>

                    {{-- FORMULARIO EDICIÓN --}}
                    @if($mostrarEdicion && $turnoEditandoId === $turno->id)
                        <div
                            class="mt-4 mb-6 w-full max-w-md rounded-2xl border border-primary/20 bg-white p-5 shadow-sm space-y-4 md:ml-10">

                            <h3 class="text-primaryDark font-semibold text-base">
                                Editar turno
                            </h3>

                            <!-- Servicio -->
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">
                                    Servicio
                                </label>
                                <select
                                    wire:model="edit_servicio_id" class="w-full rounded-lg border-gray-300 text-sm focus:ring-primary focus:border-primary">
                                    @foreach($servicios as $s)
                                        <option value="{{ $s->id }}">
                                            {{ $s->nombre_servicio }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Profesional -->
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">
                                    Profesional
                                </label>
                                <select wire:model="edit_profesional_id" class="w-full rounded-lg border-gray-300 text-sm focus:ring-primary focus:border-primary">
                                    @foreach(
                                        \App\Models\Profesional::whereHas(
                                            'especialidades',
                                            fn($q) => $q->where('especialidad_id', $edit_especialidad_id)
                                        )->with('user')->get() as $pro
                                    )
                                        <option value="{{ $pro->id }}">
                                            {{ $pro->user->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Consultorio -->
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">
                                    Consultorio
                                </label>
                                <select
                                    wire:model="edit_consultorio_id"
                                    class="w-full rounded-lg border-gray-300 text-sm
                                        focus:ring-primary focus:border-primary"
                                >
                                    @foreach($consultorios as $c)
                                        <option value="{{ $c->id }}">
                                            {{ $c->nombre_consultorio }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Fecha -->
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">
                                    Fecha
                                </label>
                                <input
                                    type="date"
                                    wire:model="edit_fecha"
                                    class="w-full rounded-lg border-gray-300 text-sm
                                        focus:ring-primary focus:border-primary"
                                >
                            </div>

                            <!-- Hora -->
                            <div>
                                <label class="text-xs font-semibold mb-2 block">Hora</label>
                                @if(count($horarios))
                                    <div class="grid grid-cols-4 gap-2">
                                        @foreach($horarios as $hora)
                                            <button
                                                type="button"
                                                wire:click="$set('edit_hora', '{{ $hora }}')"
                                                class="px-3 py-2 text-sm rounded-lg border
                                                    {{ $edit_hora === $hora ? 'bg-primary text-white border-primary' : 'bg-white hover:bg-gray-100' }}"
                                            >
                                                {{ $hora }}
                                            </button>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-sm text-red-500 mt-1">
                                        No hay horarios disponibles para esta fecha.
                                    </p>
                                @endif
                            </div>

                            <!-- BOTONES -->
                            <div class="flex flex-col sm:flex-row justify-end gap-2 pt-2">

                                <button
                                    type="button"
                                    wire:click="$set('mostrarEdicion', false)"
                                    class="w-full sm:w-auto px-4 py-2
                                        rounded-lg border text-sm
                                        hover:bg-gray-100"
                                >
                                    Cancelar
                                </button>

                                <button
                                    type="button"
                                    wire:click="actualizarTurno"
                                    class="w-full sm:w-auto px-4 py-2
                                        rounded-lg bg-primary text-white
                                        text-sm hover:bg-primaryDark"
                                >
                                    Guardar cambios
                                </button>

                            </div>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @endif
</div>
