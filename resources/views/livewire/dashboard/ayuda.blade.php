<x-layouts.usuario>

    <div class="flex items-center justify-center min-h-[70vh] px-4">
        <div class="max-w-xl w-full">

            <h2 class="text-2xl font-bold text-primary mb-3 text-center">
                Necesitás Ayuda?
            </h2>

            <p class="text-gray-600 mb-6 text-center">
                Si tenés un problema con tus turnos o accesos, revisá estas respuestas rápidas o escribinos.
            </p>

            {{-- BLOQUE FAQ --}}
            @include('livewire.dashboard.partials.faq-ayuda')

            <div class="mt-8 text-center">
                <a 
                    href="https://wa.me/5493705046526"
                    target="_blank"
                    class="inline-block bg-primary text-white px-6 py-3 rounded-xl hover:bg-primaryDark transition">
                    Consultar por WhatsApp
                </a>
            </div>

        </div>
    </div>

</x-layouts.usuario>