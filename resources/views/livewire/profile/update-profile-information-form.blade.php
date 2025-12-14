<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;

new class extends Component
{
    public string $name = '';
    public string $email = '';

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $this->name = Auth::user()->name;
        $this->email = Auth::user()->email;
    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
        ]);

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        $this->dispatch('profile-updated', name: $user->name);
    }

    /**
     * Send an email verification notification to the current user.
     */
    public function sendVerification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));

            return;
        }

        $user->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }
}; ?>

<section>
    <header class="mb-6">
        <h2 class="text-xl font-bold text-primary font-montserrat">
            Información del perfil
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            Actualizá tu nombre y correo electrónico.
        </p>
    </header>

    <form wire:submit="updateProfileInformation" class="space-y-5">

        <div>
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                Nombre
            </label>

            <input
                wire:model="name"
                id="name"
                type="text"
                required
                autofocus
                autocomplete="name"
                class="w-full rounded-xl border border-gray-300 px-4 py-2
                       focus:border-primary focus:ring-0 focus:outline-none"
            >

            @error('name')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                Correo electrónico
            </label>

            <input
                wire:model="email"
                id="email"
                type="email"
                required
                autocomplete="username"
                class="w-full rounded-xl border border-gray-300 px-4 py-2
                       focus:border-primary focus:ring-0 focus:outline-none"
            >

            @error('email')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
            @enderror

            @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! auth()->user()->hasVerifiedEmail())
                <div class="mt-3 text-sm text-gray-700">
                    Tu correo no está verificado.

                    <button
                        wire:click.prevent="sendVerification"
                        class="ml-1 text-primary font-semibold hover:underline">
                        Reenviar verificación
                    </button>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 text-sm text-green-600 font-semibold">
                            Te enviamos un nuevo enlace de verificación.
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4 pt-2">
            <button
                type="submit"
                class="bg-primary text-white px-6 py-2 rounded-xl
                       hover:bg-primaryDark transition font-semibold">
                Guardar cambios
            </button>

            <span
                x-data="{ show: false }"
                x-show="show"
                x-init="Livewire.on('profile-updated', () => { show = true; setTimeout(() => show = false, 3000) })"
                class="text-sm text-green-600 font-semibold">
                Guardado correctamente
            </span>
        </div>

    </form>
</section>
