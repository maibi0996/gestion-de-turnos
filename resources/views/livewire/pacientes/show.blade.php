<x-admin-layout>

    <div class="max-w-3xl mx-auto">

        {{-- HEADER + BOTONES --}}
        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 mb-6">

            <div>
                <h2 class="text-3xl font-bold text-gray-900 tracking-tight">
                    Detalle del paciente
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    Información registrada del paciente
                </p>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('pacientes.index') }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl
                          bg-white border border-gray-200 text-gray-700 text-sm
                          hover:bg-gray-50 transition shadow-sm">
                    ← Volver
                </a>

                <a href="{{ route('pacientes.edit', $paciente) }}"
                   class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl
                          bg-primary text-white text-sm font-semibold
                          hover:bg-primaryDark transition shadow-md">
                    Editar paciente
                </a>
            </div>
        </div>

        {{-- CARD --}}
        <div class="bg-white rounded-3xl shadow-lg border border-gray-100 p-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-10 gap-y-6">

                <div>
                    <p class="text-[11px] font-semibold tracking-wider text-gray-400 uppercase mb-1">
                        Nombre
                    </p>
                    <p class="text-base font-medium text-gray-900">
                        {{ $paciente->name }}
                    </p>
                </div>

                <div>
                    <p class="text-[11px] font-semibold tracking-wider text-gray-400 uppercase mb-1">
                        DNI
                    </p>
                    <p class="text-base font-medium text-gray-900">
                        {{ $paciente->dni ?? '—' }}
                    </p>
                </div>

                <div>
                    <p class="text-[11px] font-semibold tracking-wider text-gray-400 uppercase mb-1">
                        Teléfono
                    </p>
                    <p class="text-base font-medium text-gray-900">
                        {{ $paciente->phone ?? '—' }}
                    </p>
                </div>

                <div>
                    <p class="text-[11px] font-semibold tracking-wider text-gray-400 uppercase mb-1">
                        Correo electrónico
                    </p>
                    <p class="text-base font-medium text-gray-900">
                        {{ $paciente->email }}
                    </p>
                </div>

                <div>
                    <p class="text-[11px] font-semibold tracking-wider text-gray-400 uppercase mb-1">
                        Tipo
                    </p>
                    <p class="text-base font-medium text-gray-900">
                        {{ $paciente->tipo ?? '—' }}
                    </p>
                </div>

                <div>
                    <p class="text-[11px] font-semibold tracking-wider text-gray-400 uppercase mb-1">
                        Método de pago
                    </p>
                    <p class="text-base font-medium text-gray-900">
                        {{ $paciente->metodo_pago ?? '—' }}
                    </p>
                </div>

            </div>
        </div>

    </div>

</x-admin-layout>