<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;

new class extends Component
{
    public string $password = '';

    /**
     * Delete the currently authenticated user.
     */
    public function deleteUser(Logout $logout): void
    {
        $this->validate([
            'password' => ['required', 'string', 'current_password'],
        ]);

        tap(Auth::user(), $logout(...))->delete();

        $this->redirect('/', navigate: true);
    }
}; ?>

<section class="space-y-6 font-montserrat">
    <header>
        <h2 class="text-xl font-bold text-red-600">
            Eliminar cuenta
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            Esta acción es definitiva. Todos tus datos y turnos se eliminarán de forma permanente.
        </p>
    </header>

    <button
        x-data
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="bg-red-600 text-white px-6 py-2 rounded-xl font-semibold
               hover:bg-red-700 transition"
    >
        Eliminar cuenta
    </button>

    <x-modal name="confirm-user-deletion" :show="$errors->isNotEmpty()" focusable>
        <form wire:submit="deleteUser" class="p-8 space-y-6 text-center">

            <h2 class="text-xl font-bold text-red-600">
                ¿Seguro que querés eliminar tu cuenta?
            </h2>

            <p class="text-sm text-gray-600">
                Esta acción no se puede deshacer. Ingresá tu contraseña para confirmar.
            </p>

            <div>
                <input
                    wire:model="password"
                    id="password"
                    type="password"
                    placeholder="Contraseña"
                    class="w-full rounded-xl border border-gray-300 px-4 py-2
                           focus:border-red-500 focus:ring-0 focus:outline-none"
                >

                @error('password')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-center gap-4 pt-4">

                <button
                    type="button"
                    x-on:click="$dispatch('close')"
                    class="bg-gray-200 text-gray-700 px-6 py-2 rounded-xl
                           hover:bg-gray-300 transition font-semibold"
                >
                    Cancelar
                </button>

                <button
                    type="submit"
                    class="bg-red-600 text-white px-6 py-2 rounded-xl
                           hover:bg-red-700 transition font-semibold"
                >
                    Eliminar definitivamente
                </button>

            </div>
        </form>
    </x-modal>
</section>
