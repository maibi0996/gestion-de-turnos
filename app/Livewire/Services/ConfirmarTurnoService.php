<?php

namespace App\Livewire\Services;

use App\Models\Turno;
use Carbon\Carbon;

class ConfirmarTurnoService
{
    public function buildMessage(Turno $turno): string
    {
        $turno->loadMissing(['profesional.user', 'servicio']);

        $fecha = Carbon::parse($turno->fecha_turno)->format('d/m/Y');
        $hora  = substr($turno->hora_inicio_turno, 0, 5);

        $nombreProfesional = $turno->profesional?->user?->name ?? 'el profesional';
        $servicio          = $turno->servicio?->nombre_servicio ?? '';

        return "Hola, quiero confirmar mi turno para el {$fecha} a las {$hora} con {$nombreProfesional} para {$servicio}.";
    }


    public function buildWhatsappUrl(Turno $turno, string $phone): string
    {
        $message = $this->buildMessage($turno);

        return 'https://wa.me/'.$phone.'?text='.urlencode($message);
    }
}