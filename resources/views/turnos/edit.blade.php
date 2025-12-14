<x-admin-layout>

    <div class="max-w-4xl mx-auto">

        {{-- CABECERA --}}
        <div class="mb-8">
            <p class="text-xs uppercase tracking-widest text-primaryDark">
                Gestión de turnos
            </p>
            <h2 class="text-2xl font-semibold text-gray-900">
                Reprogramar turno
            </h2>
        </div>

        {{-- CARD --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">

            <form action="{{ route('turnos.update', $turno->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                {{-- Paciente --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Paciente
                    </label>

                    <select disabled class="w-full rounded-lg border-gray-200 bg-gray-100 text-gray-500 cursor-not-allowed">
                        @foreach($pacientes as $p)
                            @if($p->id == $turno->paciente_id)
                                <option selected>{{ $p->name }}</option>
                            @endif
                        @endforeach
                    </select>

                    {{-- valor real que se envía --}}
                    <input type="hidden" name="paciente_id" value="{{ $turno->paciente_id }}">
                </div>

                {{-- Profesional --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Profesional
                    </label>
                    <select name="profesional_id"
                            class="w-full rounded-lg border-gray-300 focus:ring-[#06c4a0] focus:border-[#06c4a0]">
                        <option value="">Sin asignar</option>
                        @foreach($profesionales as $prof)
                            <option value="{{ $prof->id }}" @selected(old('profesional_id', $turno->profesional_id) == $prof->id)>
                                {{ $prof->user->name ?? 'Profesional' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Servicio --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Servicio
                    </label>
                    <select name="servicio_id"
                            class="w-full rounded-lg border-gray-300 focus:ring-[#06c4a0] focus:border-[#06c4a0]">
                        <option value="">Sin asignar</option>
                        @foreach($servicios as $s)
                            <option value="{{ $s->id }}" @selected(old('servicio_id', $turno->servicio_id) == $s->id)>
                                {{ $s->nombre_servicio }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Fecha --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Fecha
                    </label>
                    <input type="date"
                           name="fecha_turno"
                           value="{{ old('fecha_turno', $turno->fecha_turno) }}"
                           class="w-full rounded-lg border-gray-300 focus:ring-[#06c4a0] focus:border-[#06c4a0]">
                </div>

                {{-- Hora --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-3">
                        Horario
                    </label>

                    <input type="hidden" name="hora_inicio_turno" id="hora_inicio_turno"
                        value="{{ old('hora_inicio_turno', $turno->hora_inicio_turno) }}">

                    <div class="flex flex-wrap gap-3">
                        @php
                            $horaSeleccionada = substr(old('hora_inicio_turno', $turno->hora_inicio_turno), 0, 5);
                        @endphp

                        @foreach($horarios as $hora)
                            @php
                                $ocupado = in_array($hora, $horariosOcupados);
                                $seleccionado = $horaSeleccionada === $hora;
                            @endphp

                            <button
                                type="button"
                                @unless($ocupado)
                                    onclick="
                                        document.getElementById('hora_inicio_turno').value='{{ $hora }}';

                                        document.querySelectorAll('.hora-btn')
                                            .forEach(b => { b.classList.remove( 'bg-primary', 'text-white', 'ring-2', 'ring-primary');
                                                b.classList.add( 'bg-white', 'text-gray-700', 'border-primary');
                                            });

                                        this.classList.remove( 'bg-white', 'text-gray-700', 'border-primary');
                                        this.classList.add('bg-primary','text-white','ring-2','ring-primary');
                                    "
                                @endunless
                                @disabled($ocupado)
                                class="hora-btn px-4 py-2 rounded-xl text-sm font-medium border transition
                                    {{ $ocupado
                                        ? 'bg-gray-100 text-gray-400 border-gray-200 cursor-not-allowed'
                                        : ($seleccionado
                                            ? 'bg-primary text-white ring-2 ring-primary border-primary'
                                            : 'bg-white text-gray-700 border-primary hover:bg-gray-50')
                                    }}">
                                {{ $hora }}
                            </button>
                        @endforeach
                    </div>

                    @error('hora_inicio_turno')
                        <p class="text-red-500 text-xs mt-2">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- BOTONES --}}
                <div class="pt-6 flex items-center gap-4">
                    <button type="submit"
                        class="bg-[#06c4a0] text-white px-6 py-3 rounded-xl font-semibold hover:bg-[#05b092] transition">
                        Guardar cambios
                    </button>

                    <a href="{{ route('dashboard') }}" class="text-sm text-gray-500 hover:underline">
                        Volver
                    </a>
                </div>

            </form>

        </div>
    </div>

</x-admin-layout>