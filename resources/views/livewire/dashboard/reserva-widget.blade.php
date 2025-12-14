<div class="w-full max-w-5xl mx-auto animate-fade-slide">
    <div class="flex items-center justify-center gap-8 mb-8">
        {{-- PASO 1 --}}
        <div class="flex items-center gap-2">
            <div
                class="w-8 h-8 rounded-full flex items-center justify-center font-bold
                @if($paso === 1)
                    bg-primary text-white
                @else
                    bg-white border-2 border-primary text-primary
                @endif"> 1
            </div>
            <span
                class="font-semibold
                @if($paso === 1) text-primary @else text-gray-400 @endif">
                PASO
            </span>
        </div>

        <div class="h-0.5 w-16
            @if($paso === 2) bg-primary @else bg-gray-400 @endif">
        </div>

        {{-- PASO 2 --}}
        <div class="flex items-center gap-2">
            <div
                class="w-8 h-8 rounded-full flex items-center justify-center font-bold
                @if($paso === 2)
                    bg-primary text-white
                @else
                    border-2 border-gray-300 text-gray-400
                @endif"> 2
            </div>
            <span
                class="font-semibold
                @if($paso === 2) text-primary @else text-gray-400 @endif">
                PASO
            </span>
        </div>
    </div>

    <h1 class="text-center font-title text-2xl font-bold text-primaryDark mb-10">
        Solicitar un Turno para Kinesiología
    </h1>

    @if ($paso == 1)
    <div class="bg-white border border-gray-200 shadow-xl rounded-2xl p-8 sm:p-10">
        <div class="mb-6">
            <label class="block text-gray-700 font-semibold mb-2">Elige la especialidad:</label>

            <select 
                wire:model.live="especialidad_id"
                class="select-ui w-full border border-gray-300 rounded-xl py-3 px-4 bg-white text-gray-800 focus:outline-none focus:ring-0 focus:border-primary appearance-none">
                <option value="">Seleccionar</option>
                @foreach($especialidades as $e)
                    <option value="{{ $e->id }}">{{ $e->nombre_especialidad }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-6">
            <label class="block text-gray-700 font-semibold mb-2">Elige al profesional:</label>

            <select
                wire:model="profesional_id"
                class="select-ui w-full border border-gray-300 rounded-xl py-3 px-4 bg-white text-gray-800 focus:outline-none focus:ring-0 focus:border-primary appearance-none">
                @if($profesionales->isEmpty()) disabled @endif
            >
                <option value="">Seleccionar</option>

                @foreach($profesionales as $p)
                    <option value="{{ $p->id }}">
                        {{ $p->user->name ?? 'Profesional' }} {{ $p->user->last_name ?? '' }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6">

            <div>
                <label class="block text-gray-700 font-semibold mb-2">Servicio o Motivo:</label>

                <select 
                    wire:model="servicio_id"
                    class="select-ui w-full border border-gray-300 rounded-xl py-3 px-4 bg-white text-gray-800 focus:outline-none focus:ring-0 focus:border-primary appearance-none">
                    <option value="">Seleccionar</option>
                    @foreach($servicios as $s)
                        <option value="{{ $s->id }}">{{ $s->nombre_servicio }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-gray-700 font-semibold mb-2">Sesiones:</label>

                <input 
                    type="number" 
                    min="1" 
                    wire:model="sesiones"
                    class="w-full border-gray-300 rounded-xl py-3 px-4 focus:ring-primary focus:border-primary"
                    placeholder="Cantidad"
                >
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6">

            <div>
                <label class="block text-gray-700 font-semibold mb-2">Fecha:</label>

                <input 
                    type="date" 
                    wire:model.live="fecha" wire:change="validarFecha" min="{{ now()->toDateString() }}"
                    class="w-full border-gray-300 rounded-xl py-3 px-4 focus:ring-primary focus:border-primary">

                @error('fecha')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-gray-700 font-semibold mb-2">Horario:</label>

                <div class="flex flex-wrap gap-2">
                    @foreach($horarios as $horaItem)
                        <button 
                            type="button"
                            wire:click="$set('hora', '{{ $horaItem }}')"
                            class="px-3 py-1.5 rounded-lg border text-sm 
                                {{ $hora == $horaItem 
                                    ? 'bg-primary text-white border-primary' 
                                    : 'border-gray-300 text-gray-600 bg-white' 
                                }}"
                        >
                            {{ $horaItem }}
                        </button>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="mb-8">
            <label class="block text-gray-700 font-semibold mb-2">Consultorio:</label>

            <select 
                wire:model="consultorio_id"
                class="select-ui w-full border border-gray-300 rounded-xl py-3 px-4 bg-white text-gray-800 focus:outline-none focus:ring-0 focus:border-primary appearance-none">
                <option value="">Seleccionar</option>
                @foreach($consultorios as $c)
                    <option value="{{ $c->id }}">{{ $c->nombre_consultorio }}</option>
                @endforeach
            </select>

            @error('consultorio_id') 
                <span class="text-danger text-sm">{{ $message }}</span> 
            @enderror
        </div>

        <div class="w-full flex justify-center mt-6">
            <button type="button" wire:click="guardarturno" wire:loading.attr="disabled"
                class="w-full max-w-sm bg-primary text-white font-semibold py-3 rounded-xl hover:bg-primaryDark transition disabled:opacity-50 disabled:cursor-not-allowed">
                <span wire:loading.remove wire:target="guardarturno">Pedir un Turno</span>
            </button>
        </div>

        @else
            @include('livewire.dashboard.confirmacion-turno')
        @endif
    </div>


</div>