<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use App\Models\Turno;
use App\Models\Servicio;
use App\Models\Consultorio;
use App\Models\DisponibilidadProfesional;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class MisTurnos extends Component
{
    public ?int $reprogramandoId = null;
    public string $nuevaFecha = '';
    public string $nuevaHora  = '';
    public bool $mostrarReprogramar = false;

    public bool $mostrarEdicion = false;
    public ?int $turnoEditandoId = null;

    public $edit_especialidad_id;
    public $edit_profesional_id;
    public $edit_servicio_id;
    public $edit_consultorio_id;
    public $edit_fecha;
    public $edit_hora;

    public $filtroFecha = '';
    public $filtroEstado = '';
    public $filtroMes = '';

    public $turnos;
    public $servicios;
    public $consultorios;

    public array $horarios = [];


    public function mount()
    {
        $this->cargarDatos();
    }

    public function updatedFiltroEstado() { $this->cargarDatos(); }
    public function updatedFiltroMes()    { $this->cargarDatos(); }
    public function updatedFiltroFecha()  { $this->cargarDatos(); }

    public function generarHorariosEdicion(): void
    {
        $this->horarios = [];

        if (! $this->edit_profesional_id || ! $this->edit_fecha) {
            return;
        }

        $dia = ucfirst(
            Carbon::parse($this->edit_fecha)
                ->locale('es')
                ->dayName
        );

        $disponibilidades = DisponibilidadProfesional::where(
            'profesional_id',
            $this->edit_profesional_id
        )
        ->where('dia_semana', $dia)
        ->get();

        if ($disponibilidades->isEmpty()) {
            return;
        }

        $ocupados = Turno::where('profesional_id', $this->edit_profesional_id)
            ->where('fecha_turno', $this->edit_fecha)
            ->where('id', '!=', $this->turnoEditandoId)
            ->pluck('hora_inicio_turno')
            ->map(fn ($h) => substr($h, 0, 5))
            ->toArray();

        foreach ($disponibilidades as $disp) {
            $inicio = Carbon::parse($disp->hora_inicio);
            $fin    = Carbon::parse($disp->hora_fin);

            while ($inicio < $fin) {
                $hora = $inicio->format('H:i');

                if (! in_array($hora, $ocupados)) {
                    $this->horarios[] = $hora;
                }

                $inicio->addMinutes(30);
            }
        }
    }

    public function confirmar(int $turnoId): void
    {
        $turno = Turno::where('paciente_id', Auth::id())->findOrFail($turnoId);

        $estado = strtolower(trim($turno->estado_turno ?? ''));

        if ($estado === 'pendiente') {
            $turno->estado_turno = 'Confirmado';
            $turno->save();

            $this->dispatch(
                'toast',
                type: 'success',
                message: 'Tu turno fue confirmado correctamente.'
            );
        } else {
            $this->dispatch(
                'toast',
                type: 'info',
                message: 'El turno no está en estado pendiente.'
            );
        }

        $this->cargarDatos();
    }

    public function cancelar(int $turnoId): void
    {
        $turno = Turno::where('paciente_id', Auth::id())->findOrFail($turnoId);
        $turno->estado_turno = 'Cancelado';
        $turno->save();

        $this->dispatch(
            'toast',
            type: 'success',
            message: 'Tu turno fue cancelado correctamente.'
        );

        $this->cargarDatos();
    }

    public function editar(int $id): void
    {
        $turno = Turno::with(['servicio', 'profesional.user'])->findOrFail($id);

        $this->turnoEditandoId     = $turno->id;
        $this->edit_servicio_id    = $turno->servicio_id;
        $this->edit_consultorio_id = $turno->consultorio_id;
        $this->edit_profesional_id = $turno->profesional_id;
        $this->edit_fecha          = $turno->fecha_turno;
        $this->edit_hora           = substr($turno->hora_inicio_turno, 0, 5);

        $this->edit_especialidad_id = $turno->servicio->especialidad_id ?? null;

        $this->mostrarEdicion = true;

        $this->generarHorariosEdicion();
    }

    public function updatedEditFecha()
    {
        $this->generarHorariosEdicion();
    }

    public function updatedEditProfesionalId()
    {
        $this->generarHorariosEdicion();
    }

    public function actualizarTurno(): void
    {
        $this->validate([
            'edit_servicio_id'     => 'required|exists:servicios,id',
            'edit_profesional_id'  => 'required|exists:profesionales,id',
            'edit_consultorio_id'  => 'required|exists:consultorios,id',
            'edit_fecha'           => 'required|date|after_or_equal:today',
            'edit_hora'            => 'required',
        ]);

        $turno = Turno::findOrFail($this->turnoEditandoId);

        $turno->update([
            'servicio_id'       => $this->edit_servicio_id,
            'profesional_id'    => $this->edit_profesional_id,
            'consultorio_id'    => $this->edit_consultorio_id,
            'fecha_turno'       => $this->edit_fecha,
            'hora_inicio_turno' => $this->edit_hora . ':00',
        ]);

        $this->mostrarEdicion   = false;
        $this->turnoEditandoId  = null;

        $this->dispatch(
            'toast',
            type: 'success',
            message: 'El turno se actualizó correctamente.'
        );

        $this->cargarDatos();
    }

    public function abrirReprogramar(int $turnoId): void
    {
        $turno = Turno::where('paciente_id', Auth::id())->findOrFail($turnoId);

        $this->reprogramandoId    = $turno->id;
        $this->nuevaFecha         = $turno->fecha_turno;
        $this->nuevaHora          = substr($turno->hora_inicio_turno, 0, 5);
        $this->mostrarReprogramar = true;
    }

    public function cerrarReprogramar(): void
    {
        $this->mostrarReprogramar = false;
        $this->reprogramandoId    = null;
        $this->nuevaFecha         = '';
        $this->nuevaHora          = '';
    }

    public function guardarReprogramar(): void
    {
        if (! $this->reprogramandoId) {
            return;
        }

        $this->validate([
            'nuevaFecha' => ['required', 'date', 'after_or_equal:today'],
            'nuevaHora'  => ['required'],
        ], [
            'nuevaFecha.required'       => 'Debés elegir una fecha.',
            'nuevaFecha.after_or_equal' => 'La fecha no puede ser anterior a hoy.',
            'nuevaHora.required'        => 'Debés elegir un horario.',
        ]);

        $turno = Turno::where('paciente_id', Auth::id())->findOrFail($this->reprogramandoId);

        $servicio = $turno->servicio ?: Servicio::find($turno->servicio_id);

        $inicio = Carbon::parse($this->nuevaFecha . ' ' . $this->nuevaHora . ':00');
        $fin    = (clone $inicio)->addMinutes($servicio->duracion_estimada ?? 30);

        $ocupado = Turno::where('profesional_id', $turno->profesional_id)
            ->where('consultorio_id', $turno->consultorio_id)
            ->where('fecha_turno', $inicio->toDateString())
            ->where('hora_inicio_turno', $inicio->format('H:i:s'))
            ->where('id', '!=', $turno->id)
            ->exists();

        if ($ocupado) {
            $this->dispatch('toast', type: 'error', message: 'Ese horario ya está ocupado. Elegí otro horario.');
            return;
        }

        $turno->fecha_turno       = $inicio->toDateString();
        $turno->hora_inicio_turno = $inicio->format('H:i:s');
        $turno->hora_fin_turno    = $fin->format('H:i:s');
        $turno->save();

        $this->dispatch('toast', type: 'success', message: 'Tu turno fue reprogramado correctamente.');

        $this->cerrarReprogramar();
        $this->cargarDatos();
    }

    protected function cargarDatos(): void
    {
        $query = Turno::with(['profesional.user', 'servicio'])
            ->where('paciente_id', Auth::id());

        if ($this->filtroFecha) {
            $query->whereDate('fecha_turno', $this->filtroFecha);
        }

        if ($this->filtroEstado) {
            $query->where('estado_turno', $this->filtroEstado);
        }

        if ($this->filtroMes) {
            $query->whereMonth('fecha_turno', $this->filtroMes);
        }

        $this->turnos = $query
            ->orderBy('fecha_turno')
            ->orderBy('hora_inicio_turno')
            ->get();

        $this->servicios = Servicio::orderBy('nombre_servicio')->get();
        $this->consultorios = Consultorio::orderBy('nombre_consultorio')->get();
    }

    public function render()
    {
        return view('livewire.dashboard.mis-turnos', [
            'turnos'       => $this->turnos,
            'servicios'    => $this->servicios,
            'consultorios' => $this->consultorios,
        ])->layout('components.layouts.usuario');
    }
}
