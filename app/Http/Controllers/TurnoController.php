<?php

namespace App\Http\Controllers;

use App\Models\Turno;
use App\Models\Profesional;
use App\Models\Servicio;
use Illuminate\Http\Request;
use App\Models\DisponibilidadProfesional;
use Carbon\Carbon;


class TurnoController extends Controller
{
    public function create()
    {
        $pacientes = \App\Models\User::pacientes()->orderBy('name')->get();
        $profesionales = Profesional::with('user')->get();

        $servicios = Servicio::orderBy('nombre_servicio')->get();

        return view('turnos.create', compact('pacientes', 'profesionales', 'servicios'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'paciente_id' => 'nullable|exists:users,id',
            'profesional_id' => 'nullable|exists:profesionales,id',
            'servicio_id' => 'nullable|exists:servicios,id',
            'fecha_turno' => 'required|date',
            'hora_inicio_turno' => 'required',
        ]);

        $existe = Turno::where('profesional_id', $data['profesional_id'])
            ->where('fecha_turno', $data['fecha_turno'])
            ->where('hora_inicio_turno', $data['hora_inicio_turno'])
            ->where('estado_turno', '!=', 'cancelado')
            ->exists();

        if ($existe) {
            return back()
                ->withErrors(['hora_inicio_turno' => 'Ese horario acaba de ocuparse. Por favor seleccione otro.'])
                ->withInput();
        }

        $data['estado_turno'] = 'pendiente';

        Turno::create($data);

        return redirect()->route('dashboard')->with('success', 'Turno creado correctamente.');
    }

    public function edit($id)
    {
        $turno = Turno::findOrFail($id);

        $pacientes = \App\Models\User::pacientes()->orderBy('name')->get();
        $profesionales = Profesional::with('user')->get();
        $servicios = Servicio::orderBy('nombre_servicio')->get();

        $horarios = [];
        $fecha = Carbon::parse($turno->fecha_turno);
        $diaTexto = ucfirst($fecha->locale('es')->dayName);

        $disponibilidades = DisponibilidadProfesional::where('profesional_id', $turno->profesional_id)
        ->where('dia_semana', $diaTexto)
        ->get();

        foreach ($disponibilidades as $disp) {
            $desde = Carbon::createFromFormat('H:i:s', $disp->hora_inicio);
            $hasta = Carbon::createFromFormat('H:i:s', $disp->hora_fin);

            while ($desde < $hasta) {
                $horarios[] = $desde->format('H:i');
                $desde->addMinutes(30);
            }
        }

        sort($horarios);

        $horariosOcupados = Turno::where('profesional_id', $turno->profesional_id)
        ->where('fecha_turno', $turno->fecha_turno)
        ->where('id', '!=', $turno->id)
        ->where('estado_turno', '!=', 'cancelado')
        ->pluck('hora_inicio_turno')
        ->map(fn ($h) => substr($h, 0, 5))
        ->toArray();

        return view('turnos.edit', compact('turno', 'pacientes', 'profesionales', 'servicios', 'horarios', 'horariosOcupados'));
        
    }

    public function update(Request $request, $id)
    {
        $turno = Turno::findOrFail($id);

        $existe = Turno::where('profesional_id', $request->profesional_id)
            ->where('fecha_turno', $request->fecha_turno)
            ->where('hora_inicio_turno', $request->hora_inicio_turno)
            ->where('id', '!=', $turno->id)
            ->where('estado_turno', '!=', 'cancelado')
            ->exists();

        if ($existe) {
            return back()
                ->withErrors([
                    'hora_inicio_turno' => 'Ese horario ya no se encuentra disponible.'
                ])
                ->withInput();
        }

        $request->validate([
            'paciente_id' => 'nullable|exists:users,id',
            'profesional_id' => 'nullable|exists:profesionales,id',
            'servicio_id' => 'nullable|exists:servicios,id',
            'fecha_turno' => 'required|date',
            'hora_inicio_turno' => 'required',
        ]);

        $turno->update([
            'paciente_id' => $request->paciente_id,
            'profesional_id' => $request->profesional_id,
            'servicio_id' => $request->servicio_id,
            'fecha_turno' => $request->fecha_turno,
            'hora_inicio_turno' => $request->hora_inicio_turno,
        ]);

        return redirect()
            ->route('dashboard')
            ->with('success', 'Turno reprogramado correctamente.');
    }

    public function confirmar($id)
    {
        $turno = Turno::findOrFail($id);

        $turno->estado_turno = 'confirmado';
        $turno->save();

        return redirect()->back()->with('success', 'Turno confirmado correctamente.');
    }

    public function cancelar($id)
    {
        $turno = Turno::findOrFail($id);
        $turno->estado_turno = 'cancelado';
        $turno->save();

        return redirect()->back()->with('success', 'Turno cancelado.');
    }
}
