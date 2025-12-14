<div class="space-y-4" x-data="{ open: null }">

    {{-- PREGUNTA 1 --}}
    <div class="border border-gray-200 rounded-xl overflow-hidden">
        <button 
            @click="open === 1 ? open = null : open = 1"
            class="w-full text-left px-4 py-3 font-semibold flex justify-between items-center">
            ¿Cómo saco un turno?
            <span x-show="open !== 1">+</span>
            <span x-show="open === 1">−</span>
        </button>

        <div x-show="open === 1" x-collapse class="px-4 pb-4 text-gray-600">
            Desde el menú “Inicio”, elegís especialidad, profesional, servicio, fecha y horario. Luego confirmás tu turno.
        </div>
    </div>

    {{-- PREGUNTA 2 --}}
    <div class="border border-gray-200 rounded-xl overflow-hidden">
        <button 
            @click="open === 2 ? open = null : open = 2"
            class="w-full text-left px-4 py-3 font-semibold flex justify-between items-center">
            ¿Cómo confirmo mi turno?
            <span x-show="open !== 2">+</span>
            <span x-show="open === 2">−</span>
        </button>

        <div x-show="open === 2" x-collapse class="px-4 pb-4 text-gray-600">
            Una vez generado, tocá el botón “Confirmar turno”. El estado pasará a Confirmado y podrás avisar por WhatsApp a la profesional.
        </div>
    </div>

    {{-- PREGUNTA 3 --}}
    <div class="border border-gray-200 rounded-xl overflow-hidden">
        <button 
            @click="open === 3 ? open = null : open = 3"
            class="w-full text-left px-4 py-3 font-semibold flex justify-between items-center">
            ¿Puedo reprogramar un turno?
            <span x-show="open !== 3">+</span>
            <span x-show="open === 3">−</span>
        </button>

        <div x-show="open === 3" x-collapse class="px-4 pb-4 text-gray-600">
            Sí. Desde “Mis turnos” tocás el botón “Reprogramar” y elegís una nueva fecha y horario disponible.
        </div>
    </div>

    {{-- PREGUNTA 4 --}}
    <div class="border border-gray-200 rounded-xl overflow-hidden">
        <button 
            @click="open === 4 ? open = null : open = 4"
            class="w-full text-left px-4 py-3 font-semibold flex justify-between items-center">
            ¿Cómo cancelo un turno?
            <span x-show="open !== 4">+</span>
            <span x-show="open === 4">−</span>
        </button>

        <div x-show="open === 4" x-collapse class="px-4 pb-4 text-gray-600">
            Ingresá a “Mis turnos” y presioná el botón “Cancelar” en el turno que no puedas asistir.
        </div>
    </div>

    {{-- PREGUNTA 5 --}}
    <div class="border border-gray-200 rounded-xl overflow-hidden">
        <button 
            @click="open === 5 ? open = null : open = 5"
            class="w-full text-left px-4 py-3 font-semibold flex justify-between items-center">
            ¿Qué pasa si no confirmo el turno?
            <span x-show="open !== 5">+</span>
            <span x-show="open === 5">−</span>
        </button>

        <div x-show="open === 5" x-collapse class="px-4 pb-4 text-gray-600">
            El turno queda en estado pendiente y puede anularse automáticamente si no se confirma dentro del plazo.
        </div>
    </div>
</div>