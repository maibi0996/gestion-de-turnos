<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar contraseña</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
</head>

<body class="min-h-screen bg-gradient-to-b from-[#ccecff] to-white antialiased font-sans">

<div class="min-h-screen flex items-center justify-center px-4">
    <div class="w-full max-w-md">

        <div class="flex flex-col items-center mb-8">
            <x-application-logo class="w-48 h-auto mb-2 animate-fade-slide" />
        </div>

        <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">

            <h2 class="text-xl font-semibold text-gray-800 mb-4 text-center">
                ¿Olvidaste tu contraseña?
            </h2>

            @if (session('message'))
                <div class="mb-4 text-sm text-green-700 bg-green-100 border border-green-200 rounded-md px-3 py-2">
                    {{ session('message') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 text-sm text-red-700 bg-red-100 border border-red-200 rounded-md px-3 py-2">
                    {{ session('error') }}
                </div>
            @endif

            <form wire:submit.prevent="sendResetLink" class="space-y-4">

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">
                        Correo electrónico
                    </label>

                    <input type="email"
                           wire:model="email"
                           required
                           class="w-full rounded-md border-gray-300 focus:border-primary focus:ring-primary">

                    @error('email')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit"
                        class="w-full inline-flex justify-center px-4 py-2.5 rounded-md
                               bg-primary text-white font-semibold hover:bg-primaryDark
                               focus:outline-none focus:ring-2 focus:ring-offset-2
                               focus:ring-primary transition">
                    Enviar enlace
                </button>

            </form>

            <p class="text-center text-sm text-gray-600 mt-4">
                <a href="{{ route('login') }}" class="text-primary hover:underline">
                    Volver a iniciar sesión
                </a>
            </p>

        </div>

    </div>
</div>

</body>
</html>
