<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Livewire\Volt\Component;

new class extends Component
{
    public string $current_password = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Update the password for the currently authenticated user.
     */
    public function updatePassword(): void
    {
        try {
            $validated = $this->validate([
                'current_password' => ['required', 'string', 'current_password'],
                'password' => ['required', 'string', Password::defaults(), 'confirmed'],
            ]);
        } catch (ValidationException $e) {
            $this->reset('current_password', 'password', 'password_confirmation');

            throw $e;
        }

        Auth::user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        $this->reset('current_password', 'password', 'password_confirmation');

        $this->dispatch('password-updated');
    }
}; ?>

<section>
    <header class="mb-6">
        <h2 class="text-xl font-bold text-primary font-montserrat">
            Actualizar contraseña
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            Usá una contraseña segura para proteger tu cuenta.
        </p>
    </header>

    <form wire:submit="updatePassword" class="space-y-5">

        <div>
            <label for="update_password_current_password" class="block text-sm font-medium text-gray-700 mb-1">
                Contraseña actual
            </label>

            <input
                wire:model="current_password"
                id="update_password_current_password"
                type="password"
                autocomplete="current-password"
                class="w-full rounded-xl border border-gray-300 px-4 py-2
                       focus:border-primary focus:ring-0 focus:outline-none"
            >

            @error('current_password')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="update_password_password" class="block text-sm font-medium text-gray-700 mb-1">
                Nueva contraseña
            </label>

            <input
                wire:model="password"
                id="update_password_password"
                type="password"
                autocomplete="new-password"
                class="w-full rounded-xl border border-gray-300 px-4 py-2
                       focus:border-primary focus:ring-0 focus:outline-none"
            >

            @error('password')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="update_password_password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                Confirmar nueva contraseña
            </label>

            <input
                wire:model="password_confirmation"
                id="update_password_password_confirmation"
                type="password"
                autocomplete="new-password"
                class="w-full rounded-xl border border-gray-300 px-4 py-2
                       focus:border-primary focus:ring-0 focus:outline-none"
            >

            @error('password_confirmation')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center gap-4 pt-2">
            <button
                type="submit"
                class="bg-primary text-white px-6 py-2 rounded-xl
                       hover:bg-primaryDark transition font-semibold">
                Guardar nueva contraseña
            </button>

            <span
                x-data="{ show: false }"
                x-show="show"
                x-init="Livewire.on('password-updated', () => { show = true; setTimeout(() => show = false, 3000) })"
                class="text-sm text-green-600 font-semibold">
                Contraseña actualizada
            </span>
        </div>

    </form>
</section>
