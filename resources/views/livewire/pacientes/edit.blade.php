<x-admin-layout>
    <div class="max-w-3xl mx-auto mt-6 space-y-6">

        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">
                    Editar paciente
                </h2>
                <p class="text-sm text-gray-500">
                    Actualizá los datos del paciente seleccionado.
                </p>
            </div>

            <a href="{{ route('pacientes.index') }}"
               class="inline-flex items-center px-4 py-2 text-sm font-semibold rounded-lg
                      border border-gray-300 text-gray-700 hover:bg-gray-50 transition">
                Volver
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">

            <form wire:submit.prevent="save" class="space-y-4">

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Nombre completo
                    </label>
                    <input type="text" wire:model.defer="name"
                           class="w-full rounded-md border-gray-300 focus:border-primary focus:ring-primary">
                    @error('name')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            DNI
                        </label>
                        <input type="text" wire:model.defer="dni"
                               class="w-full rounded-md border-gray-300 focus:border-primary focus:ring-primary">
                        @error('dni')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Teléfono
                        </label>
                        <input type="text" wire:model.defer="phone"
                               class="w-full rounded-md border-gray-300 focus:border-primary focus:ring-primary">
                        @error('phone')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Correo electrónico
                    </label>
                    <input type="email" wire:model.defer="email"
                           class="w-full rounded-md border-gray-300 focus:border-primary focus:ring-primary">
                    @error('email')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Tipo
                        </label>
                        <select wire:model.defer="tipo"
                                class="w-full rounded-md border-gray-300 focus:border-primary focus:ring-primary">
                            <option value="">— Seleccionar —</option>
                            <option value="Particular">Particular</option>
                            <option value="Obra Social">Obra Social</option>
                        </select>
                        @error('tipo')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Método de pago
                        </label>
                        <select wire:model.defer="metodo_pago"
                                class="w-full rounded-md border-gray-300 focus:border-primary focus:ring-primary">
                            <option value="">— Seleccionar —</option>
                            <option value="Pendiente">Pendiente</option>
                            <option value="Efectivo">Efectivo</option>
                            <option value="Transferencia">Transferencia</option>
                        </select>
                        @error('metodo_pago')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div> --}}

                <div class="pt-4">
                    <button type="submit"
                            class="inline-flex justify-center px-6 py-2.5 rounded-lg bg-primary text-white
                                   font-semibold hover:bg-primaryDark transition">
                        Guardar cambios
                    </button>
                </div>

            </form>

        </div>
    </div>
</x-admin-layout>
