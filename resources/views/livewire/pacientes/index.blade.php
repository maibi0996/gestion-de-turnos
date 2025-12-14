<x-admin-layout>
    @php
        $totalPacientes = \App\Models\User::pacientes()->count();
    @endphp

    <div class="w-full flex justify-end mb-4 pr-2">
        <a href="{{ route('pacientes.create') }}"
           class="px-6 py-2.5 rounded-lg bg-primary text-white font-semibold shadow hover:bg-primaryDark transition">
            + Nuevo paciente
        </a>
    </div>

    <section class="mt-8 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">

        <h3 class="text-xl font-semibold text-gray-900 mb-4">Listado de Pacientes</h3>

        {{-- COMPUTADORA --}}
        <div class="hidden md:block overflow-hidden rounded-xl border border-gray-200">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="p-3 text-left">Apellido y Nombre</th>
                        <th class="p-3 text-left">DNI</th>
                        <th class="p-3 text-left">Teléfono</th>
                        <th class="p-3 text-left">Email</th>
                        <th class="p-3 text-right">Acciones</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">
                @foreach ($items as $paciente)
                    <tr>
                        <td class="p-3 font-medium text-gray-800">{{ $paciente->name }}</td>
                        <td class="p-3 text-gray-700">{{ $paciente->dni ?? '—' }}</td>
                        <td class="p-3 text-gray-700">{{ $paciente->phone ?? '—' }}</td>
                        <td class="p-3 text-gray-700">{{ $paciente->email }}</td>

                        <td class="p-3 text-right">
                            <div class="flex justify-end gap-2" x-data="{ confirm:false }">

                                <a href="{{ route('pacientes.show', $paciente) }}"
                                   class="px-3 py-1 rounded-lg border text-sm">
                                    Ver
                                </a>

                                <a href="{{ route('pacientes.edit', $paciente) }}"
                                   class="px-3 py-1 rounded-lg bg-primary text-white text-sm">
                                    Editar
                                </a>

                                <button type="button" @click="confirm = true"
                                    class="px-3 py-1 rounded-lg border border-red-300 text-red-600 text-sm hover:bg-red-50">
                                    Eliminar
                                </button>
                                
                                {{-- MODAL --}}
                                <div x-show="confirm" x-transition class="fixed inset-0 z-50 flex items-center justify-center bg-black/40" >
                                    <div @click.outside="confirm=false" class="w-full max-w-xs rounded-xl bg-white p-5 shadow-xl">

                                        <h3 class="text-base font-semibold text-gray-900 mb-2">
                                            Eliminar paciente
                                        </h3>

                                        <p class="text-sm text-gray-600 mb-5">
                                            ¿Seguro que querés eliminar este paciente?
                                        </p>

                                        <div class="flex justify-end gap-3">
                                            <button
                                                @click="confirm=false"
                                                class="px-3 py-1 text-sm rounded-lg border hover:bg-gray-100">
                                                Cancelar
                                            </button>

                                            <form action="{{ route('pacientes.destroy', $paciente) }}" method="POST">
                                                @csrf
                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    class="px-3 py-1 text-sm rounded-lg bg-red-600 text-white hover:bg-red-700">
                                                    Eliminar
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        {{--  MOVIL  --}}
        <div class="md:hidden space-y-4">
        @foreach ($items as $paciente)
            <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm space-y-3">

                <!-- Nombre -->
                <div>
                    <p class="text-xs uppercase text-gray-400">Paciente</p>
                    <p class="font-semibold text-gray-900">{{ $paciente->name }}</p>
                </div>

                <!-- Datos -->
                <div class="grid grid-cols-2 gap-3 text-sm">
                    <div>
                        <p class="text-xs uppercase text-gray-400">DNI</p>
                        <p>{{ $paciente->dni ?? '—' }}</p>
                    </div>

                    <div>
                        <p class="text-xs uppercase text-gray-400">Teléfono</p>
                        <p>{{ $paciente->phone ?? '—' }}</p>
                    </div>

                    <div class="col-span-2">
                        <p class="text-xs uppercase text-gray-400">Email</p>
                        <p class="truncate">{{ $paciente->email }}</p>
                    </div>
                </div>

                <!-- Acciones -->
                <div class="flex justify-end" x-data="{ open:false, confirm:false }" x-init="confirm=false; open=false">

                    <!-- BOTÓN ⋯ -->
                    <button
                        @click="open = !open"
                        class="h-9 w-9 flex items-center justify-center rounded-full
                            border border-gray-300 bg-white text-gray-600 hover:bg-gray-100">
                        ⋯
                    </button>

                    <!-- MENÚ -->
                    <div
                        x-show="open"
                        @click.outside="open=false"
                        x-transition
                        class="absolute right-4 mt-10 w-36 rounded-xl bg-white
                            border border-gray-200 shadow-lg z-40">

                        <a href="{{ route('pacientes.show', $paciente) }}"
                        class="block px-4 py-2 text-sm hover:bg-gray-100">
                            Ver
                        </a>

                        <a href="{{ route('pacientes.edit', $paciente) }}"
                        class="block px-4 py-2 text-sm text-primary hover:bg-primary/10">
                            Editar
                        </a>

                        <button
                            @click="open=false; confirm=true"
                            class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                            Eliminar
                        </button>
                    </div>

                    <!-- MODAL CONFIRMACIÓN -->
                    <div
                        x-show="confirm" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/40"
                        x-transition class="fixed inset-0 z-50 flex items-center justify-center bg-black/40">

                        <div
                            @click.outside="confirm=false"
                            class="w-full max-w-xs rounded-xl bg-white p-5 shadow-xl">

                            <h3 class="text-base font-semibold text-gray-900 mb-2">
                                Eliminar paciente
                            </h3>

                            <p class="text-sm text-gray-600 mb-5">
                                ¿Seguro que querés eliminar este paciente?
                            </p>

                            <div class="flex justify-end gap-3">
                                <button
                                    @click="confirm=false"
                                    class="px-3 py-1 text-sm rounded-lg border hover:bg-gray-100">
                                    Cancelar
                                </button>

                                <form action="{{ route('pacientes.destroy', $paciente) }}" method="POST">
                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="px-3 py-1 text-sm rounded-lg bg-red-600 text-white hover:bg-red-700">
                                        Eliminar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        @endforeach
        </div>

        <div class="mt-4">
            {{ $items->links() }}
        </div>
    </section>
</x-admin-layout>