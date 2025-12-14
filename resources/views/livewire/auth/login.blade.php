<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
</head>

<body class="min-h-screen bg-gradient-to-b from-[#ccecff] to-white antialiased font-sans">
<div class="min-h-screen flex items-center justify-center px-4">
    <div class="w-full max-w-md">
        <div class="flex flex-col items-center mb-8">
            <x-application-logo class="w-48 h-auto mb-2 animate-fade-slide" />
        </div>

        @if (session('status'))
            <div class="mb-4 text-sm text-green-700 bg-green-100 border border-green-200 rounded-md px-3 py-2">
                {{ session('status') }}
            </div>
        @endif

        <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">
                        Correo electrónico
                    </label>

                    <input type="email" name="email" value="{{ old('email') }}" required
                           class="w-full rounded-md border-gray-300 focus:border-primary focus:ring-primary">

                    @error('email')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">
                        Contraseña
                    </label>

                    <div class="relative">
                        <input id="password" type="password" name="password" required class="w-full rounded-md border-gray-300 focus:border-primary focus:ring-primary pr-10">
                        <button type="button" id="togglePassword" class="absolute inset-y-0 right-3 flex items-center text-gray-500" >
                            <!-- Ojo abierto -->
                            <svg id="icon-eye" xmlns="http://www.w3.org/2000/svg"  fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>

                            <!-- Ojo tachado -->
                            <svg id="icon-eye-off" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 hidden">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c1.658 0 3.236-.33 4.676-.936M6.31 6.31A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.5a10.523 10.523 0 01-4.293 5.774M6.31 6.31L3 3m3.31 3.31l12.38 12.38" />
                            </svg>
                        </button>
                    </div>

                    @error('password')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between">

                    <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                        <input type="checkbox"
                               name="remember"
                               class="rounded border-gray-300 text-primary focus:ring-primary">
                        Recordame porfa
                    </label>

                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}"
                           class="text-sm text-primary hover:underline">
                            Olvidé mi contraseña
                        </a>
                    @endif
                </div>
                <button type="submit"
                        class="w-full inline-flex justify-center px-4 py-2.5 rounded-md
                               bg-primary text-white font-semibold hover:bg-primaryDark
                               focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition">
                    Iniciar Sesión
                </button>

            </form>
        </div>

        @if (Route::has('register'))
            <p class="text-center text-sm text-gray-600 mt-4">
                ¿Nuevo por acá?
                <a href="{{ route('register') }}" class="text-primary hover:underline">
                    Crear cuenta
                </a>
            </p>
        @endif
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const toggle = document.getElementById("togglePassword");
    const password = document.getElementById("password");
    const iconEye = document.getElementById("icon-eye");
    const iconEyeOff = document.getElementById("icon-eye-off");

    toggle.addEventListener("click", () => {
        const isPassword = password.type === "password";
        password.type = isPassword ? "text" : "password";
        iconEye.classList.toggle("hidden", !isPassword);
        iconEyeOff.classList.toggle("hidden", isPassword);
    });
});
</script>
</body>
</html>

{{-- <!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
</head>

<body class="min-h-screen bg-gradient-to-b from-[#ccecff] to-white antialiased font-sans">
<div class="min-h-screen flex items-center justify-center px-4">
    <div class="w-full max-w-md">
        <div class="flex flex-col items-center mb-8">
            <x-application-logo class="w-48 h-auto mb-2 animate-fade-slide" />
        </div>

        @if (session('status'))
            <div class="mb-4 text-sm text-green-700 bg-green-100 border border-green-200 rounded-md px-3 py-2">
                {{ session('status') }}
            </div>
        @endif

        <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">
                        Correo electrónico
                    </label>

                    <input type="email" name="email" value="{{ old('email') }}" required
                           class="w-full rounded-md border-gray-300 focus:border-primary focus:ring-primary">

                    @error('email')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">
                        Contraseña
                    </label>

                    <div class="relative">
                        <input id="password" type="password" name="password" required class="w-full rounded-md border-gray-300 focus:border-primary focus:ring-primary pr-10">

                        <button type="button" id="togglePassword" class="absolute inset-y-0 right-3 flex items-center text-gray-500" >
                            <!-- Ojo abierto -->
                            <svg id="icon-eye" xmlns="http://www.w3.org/2000/svg"  fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>

                            <!-- Ojo tachado -->
                            <svg id="icon-eye-off" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 hidden">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c1.658 0 3.236-.33 4.676-.936M6.31 6.31A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.5a10.523 10.523 0 01-4.293 5.774M6.31 6.31L3 3m3.31 3.31l12.38 12.38" />
                            </svg>
                        </button>
                    </div>

                    @error('password')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex items-center justify-between">

                    <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                        <input type="checkbox"
                               name="remember"
                               class="rounded border-gray-300 text-primary focus:ring-primary">
                        Recordame porfa
                    </label>

                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}"
                           class="text-sm text-primary hover:underline">
                            Olvidé mi contraseña
                        </a>
                    @endif
                </div>
                <button type="submit"
                        class="w-full inline-flex justify-center px-4 py-2.5 rounded-md
                               bg-primary text-white font-semibold hover:bg-primaryDark
                               focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition">
                    Iniciar Sesión
                </button>

            </form>
        </div>

        @if (Route::has('register'))
            <p class="text-center text-sm text-gray-600 mt-4">
                ¿Nuevo por acá?
                <a href="{{ route('register') }}" class="text-primary hover:underline">
                    Crear cuenta
                </a>
            </p>
        @endif

    </div>
</div>
<script>
document.addEventListener("DOMContentLoaded", () => {
    const toggle = document.getElementById("togglePassword");
    const password = document.getElementById("password");
    const iconEye = document.getElementById("icon-eye");
    const iconEyeOff = document.getElementById("icon-eye-off");

    toggle.addEventListener("click", () => {
        const isPassword = password.type === "password";
        password.type = isPassword ? "text" : "password";
        iconEye.classList.toggle("hidden", !isPassword);
        iconEyeOff.classList.toggle("hidden", isPassword);
    });
});
</script>
</body>
</html> --}}
