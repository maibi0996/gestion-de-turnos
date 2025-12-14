<?php

namespace App\Livewire\Services;

use App\Models\Turno;
use App\Models\Servicio;
use Carbon\Carbon;

class CrearTurnoService
{
    public function crearTurnos(array $data): array
    {

        $fechaTurno = Carbon::parse($data['fecha'])->toDateString();
        $horaTurno  = $data['hora'] . ':00';
        $ahora      = Carbon::now();

        if ($fechaTurno === $ahora->toDateString()) {
            $horaSeleccionada = Carbon::parse($fechaTurno . ' ' . $horaTurno);

            if ($horaSeleccionada->lte($ahora)) {
                throw new \RuntimeException(
                    "La hora seleccionada ya pasó para el día de hoy."
                );
            }
        }

        $servicio = Servicio::findOrFail($data['servicio_id']);

        for ($i = 0; $i < $data['sesiones']; $i++) {
            $fechaActual = $this->calcularFechaSesion($data['fecha'], $i);
            $inicio = Carbon::parse($fechaActual . ' ' . $horaTurno);

            $ocupado = Turno::where('profesional_id', $data['profesional_id'])
                ->where('consultorio_id', $data['consultorio_id'])
                ->where('fecha_turno', $fechaActual)
                ->where('hora_inicio_turno', $inicio->format('H:i:s'))
                ->exists();

            if ($ocupado) {
                throw new \RuntimeException(
                    "Ya existe un turno el {$inicio->format('d/m')} a las {$inicio->format('H:i')}."
                );
            }
        }

        $resumen = [];
        $turnoReferencia = null;

        for ($i = 0; $i < $data['sesiones']; $i++) {
            $fechaActual = $this->calcularFechaSesion($data['fecha'], $i);

            $inicio = Carbon::parse($fechaActual . ' ' . $horaTurno);
            $fin = (clone $inicio)->addMinutes($servicio->duracion_estimada);

            $turno = Turno::create([
                'paciente_id'       => $data['paciente_id'],
                'profesional_id'    => $data['profesional_id'],
                'servicio_id'       => $data['servicio_id'],
                'consultorio_id'    => $data['consultorio_id'],
                'fecha_turno'       => $fechaActual,
                'hora_inicio_turno' => $inicio->format('H:i:s'),
                'hora_fin_turno'    => $fin->format('H:i:s'),
                'estado_turno'      => 'Pendiente',
            ]);

            if ($i === 0) {
                $turnoReferencia = $turno;
            }

            $resumen[] = [
                'fecha' => $inicio->format('d/m/Y'),
                'hora'  => $inicio->format('H:i'),
            ];
        }

        return [
            'resumen'         => $resumen,
            'turnoReferencia' => $turnoReferencia,
        ];
    }

    protected function calcularFechaSesion(string $fechaBase, int $offsetDias): string
    {
        $fecha = Carbon::parse($fechaBase)->addDays($offsetDias);

        while ($fecha->isWeekend()) {
            $fecha->addDay();
        }

        return $fecha->toDateString();
    }
}
