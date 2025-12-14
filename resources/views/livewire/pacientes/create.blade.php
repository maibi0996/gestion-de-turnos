<x-admin-layout>

    <div class="max-w-3xl mx-auto mt-10">

        <!-- HEADER -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Nuevo paciente</h1>
                <p class="text-sm text-gray-500">
                    Completá los datos para registrar un nuevo paciente.
                </p>
            </div>

            <a href="{{ route('pacientes.index') }}"
               class="px-4 py-2 rounded-lg border text-sm hover:bg-gray-100">
                Volver
            </a>
        </div>

        <!-- CARD -->
        <div class="rounded-2xl bg-white p-6 shadow-sm border border-gray-200">

            <form wire:submit.prevent="save" class="space-y-6">
                <!-- NOMBRE / APELLIDO -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Nombre
                        </label>
                        <input type="text" wire:model.defer="name"
                            class="w-full rounded-lg border-gray-300 text-sm"
                            required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Apellido
                        </label>
                        <input type="text" wire:model.defer="last_name"
                            class="w-full rounded-lg border-gray-300 text-sm"
                            required>
                    </div>
                </div>

                <!-- EMAIL -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Correo electrónico
                    </label>
                    <input type="email" wire:model.defer="email"
                        class="w-full rounded-lg border-gray-300 text-sm"
                        required>
                </div>

                <!-- TELÉFONO / NACIMIENTO -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Teléfono
                        </label>
                        <input type="text" wire:model.defer="phone"
                            class="w-full rounded-lg border-gray-300 text-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Fecha de nacimiento
                        </label>
                        <input type="date" wire:model.defer="birthdate"
                            class="w-full rounded-lg border-gray-300 text-sm">
                    </div>
                </div>

                <!-- BOTONES -->
                <div class="flex justify-end gap-3 pt-4">
                    <a href="{{ route('pacientes.index') }}"
                    class="px-4 py-2 rounded-lg border text-sm hover:bg-gray-100">
                        Cancelar
                    </a>

                    <button type="submit"
                        class="px-5 py-2 rounded-lg bg-primary text-white text-sm hover:bg-primaryDark">
                        Guardar paciente
                    </button>
                </div>

            </form>
        </div>
    </div>

</x-admin-layout>