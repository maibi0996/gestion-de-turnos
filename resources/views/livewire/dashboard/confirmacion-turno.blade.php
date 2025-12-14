<div class="bg-white border border-gray-200 shadow-xl rounded-2xl p-8 sm:p-10">

    <h2 class="text-xl font-bold text-primaryDark mb-4">
        Turnos generados correctamente
    </h2>

    @if (!empty($resumenTurnos))
        <div class="mb-6">
            <p class="text-sm text-gray-600 mb-3">
                Estos son tus turnos:
            </p>

            <div class="overflow-hidden rounded-xl border border-gray-200 bg-white">
                <table class="w-full text-sm">
                    <tbody>
                        @foreach ($resumenTurnos as $item)
                            <tr class="border-b last:border-0">
                                <td class="px-4 py-3 font-medium text-gray-800">
                                    {{ $item['fecha'] }}
                                </td>
                                <td class="px-4 py-3 text-gray-600">
                                    {{ $item['hora'] }} hs
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    @if ($this->whatsappMessage)
        <div class="mb-6 rounded-2xl border border-primary/20 bg-primary/5 p-4 space-y-3">
            <p class="text-xs font-semibold text-gray-500">
                Este mensaje envialo al WhatsApp para Confirmar
            </p>
            <p class="rounded-lg bg-gray-50 px-3 py-3 text-sm text-gray-800 whitespace-pre-line">
                {{ $this->whatsappMessage }}
            </p>
        </div>
    @endif

    <div class="flex flex-wrap gap-3 mb-6">
        {{-- Confirmar turno --}}
        @if($this->turnoCreado?->estado_turno !== 'Confirmado')
            <button type="button"
                wire:click="confirmarTurno"
                class="bg-emerald-600 text-white px-6 py-2 rounded-xl hover:bg-emerald-700 transition">
                Confirmar turno
            </button>
        @else
            <span class="inline-flex items-center rounded-full bg-emerald-100 text-emerald-700 px-4 py-2 text-sm font-semibold">
                Turno confirmado
            </span>
        @endif

        {{-- Editar --}}
        <button 
            type="button"
            wire:click="volverAEditar"
            class="bg-gray-100 text-gray-700 px-6 py-2 rounded-xl hover:bg-gray-200 transition">
            Editar
        </button>

        {{-- Enviar por WhatsApp --}}
        @if($this->whatsappUrl)
            <a 
                href="{{ $this->whatsappUrl }}"
                target="_blank"
                class="bg-primary text-white px-6 py-2 rounded-xl hover:bg-primaryDark transition inline-flex items-center">
                Enviar por WhatsApp
            </a>
        @endif
    </div>

    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('abrir-whatsapp', payload => {

                // Para depurar
                console.log('Evento recibido:', payload);

                let url = payload.url ?? payload[0]?.url;

                if (!url) {
                    console.error('No llegó la URL al frontend');
                    return;
                }

                window.open(url, '_blank');
            });
        });
    </script>

    <div class="flex justify-between pt-2">
        <button type="button" wire:click="irAMisTurnos" class="text-sm font-medium text-gray-600 hover:text-primaryDark">
            Ver todos mis turnos
        </button>
    </div>
</div>