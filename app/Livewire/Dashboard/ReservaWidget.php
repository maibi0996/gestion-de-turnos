<?php  

namespace App\Livewire\Dashboard;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Livewire\Services\CrearTurnoService;
use App\Livewire\Services\ConfirmarTurnoService;
use App\Models\Profesional;
use App\Models\Especialidad;
use App\Models\Servicio;
use App\Models\Consultorio;
use App\Models\Turno;
use App\Models\DisponibilidadProfesional;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\TurnoConfirmado;

class ReservaWidget extends Component
{
    public $paso = 1;
    public $sesiones = 1;
    public $profesional_id = '';
    public $especialidad_id = '';
    public $servicio_id = '';
    public $consultorio_id = '';
    public $fecha = '';
    public $hora = '';
    public $horarios = [];
    public $resumenTurnos = [];

    public $fechasBloqueadas = [];

    public ?Turno $turnoCreado = null;

    public function updatedEspecialidad_id()
    {
        $this->reset(['profesional_id', 'servicio_id', 'hora', 'horarios']);
    }

    public function updatedProfesional_id()
    {
        $this->reset(['hora', 'horarios']);
        $this->generarHorarios();
        $this->generarFechasBloqueadas();
    }

    public function updatedServicio_id()
    {
        $this->horarios = [];

        if (!$this->servicio_id) return;

        $servicio = Servicio::find($this->servicio_id);

        if ($servicio) {
            $this->horarios = \App\Livewire\Dashboard\Horarios::generar(
                $servicio->duracion_estimada
            );
        }
    }

    public function updatedFecha()
    {
        $this->generarHorarios();
    }

    public function validarFecha()
    {
        if (!$this->fecha) return;

        $fechaCarbon = Carbon::parse($this->fecha)->startOfDay();
        $hoy = now()->startOfDay();

        if ($fechaCarbon->lt($hoy)) {
            $this->dispatch('toast', type:'error', message:'La fecha no puede ser anterior a hoy.');
            $this->fecha = '';
            return;
        }

        if ($fechaCarbon->isWeekend()) {
            $this->dispatch('toast', type:'error', message:'No se atiende los fines de semana.');
            $this->fecha = '';
            return;
        }

        if (in_array($this->fecha, $this->fechasBloqueadas ?? [])) {
            $this->dispatch('toast', type:'error', message:'Esa fecha no está disponible.');
            $this->fecha = '';
            return;
        }

        $diaSemana = ucfirst($fechaCarbon->locale('es')->dayName);

        $hayDisponibilidad = DisponibilidadProfesional::where('profesional_id', $this->profesional_id)
            ->where('dia_semana', $diaSemana)
            ->exists();

        if (!$hayDisponibilidad) {
            $this->dispatch('toast', type:'error', message:'El profesional no atiende ese día.');
            $this->fecha = '';
            return;
        }

        $this->generarHorarios();
    }

    public function generarHorarios()
    {
        $this->horarios = [];

        if (!$this->profesional_id || !$this->fecha) return;

        $dia = ucfirst(Carbon::parse($this->fecha)->locale('es')->dayName);

        $disponibilidades = DisponibilidadProfesional::where('profesional_id', $this->profesional_id)
            ->where('dia_semana', $dia)
            ->get();

        if ($disponibilidades->isEmpty()) return;

        $ocupados = Turno::where('profesional_id', $this->profesional_id)
            ->where('fecha_turno', $this->fecha)
            ->where('estado_turno', 'Confirmado')
            ->pluck('hora_inicio_turno')
            ->map(fn($h) => substr($h, 0, 5))
            ->toArray();

        $hoy = Carbon::today()->toDateString();
        $horaActual = Carbon::now()->format('H:i');

        foreach ($disponibilidades as $disp) {
            $inicio = Carbon::parse($disp->hora_inicio);
            $fin    = Carbon::parse($disp->hora_fin);

            while ($inicio < $fin) {
                $hora = $inicio->format('H:i');

                if ($this->fecha === $hoy && $hora < $horaActual) {
                    $inicio->addMinutes(30);
                    continue;
                }

                if (in_array($hora, $ocupados)) {
                    $inicio->addMinutes(30);
                    continue;
                }

                $this->horarios[] = $hora;

                $inicio->addMinutes(30);
            }
        }
    }

    public function generarFechasBloqueadas()
    {
        if (!$this->profesional_id) return;

        $diasDisponibles = DisponibilidadProfesional::where('profesional_id', $this->profesional_id)
            ->pluck('dia_semana')
            ->map(fn($d) => strtolower($d))
            ->toArray();

        $map = [
            'lunes' => 1,
            'martes' => 2,
            'miércoles' => 3,
            'miercoles' => 3,
            'jueves' => 4,
            'viernes' => 5,
            'sábado' => 6,
            'sabado' => 6,
            'domingo' => 0,
        ];

        $diasDisponiblesNum = array_map(fn($d) => $map[$d], $diasDisponibles);

        $inicio = now();
        $fin = now()->addMonths(3);

        $this->fechasBloqueadas = [];

        for ($d = $inicio->copy(); $d <= $fin; $d->addDay()) {

            $dow = $d->dayOfWeek;

            if ($dow === 0 || $dow === 6) {
                $this->fechasBloqueadas[] = $d->format('Y-m-d');
                continue;
            }

            if (!in_array($dow, $diasDisponiblesNum)) {
                $this->fechasBloqueadas[] = $d->format('Y-m-d');
            }
        }
    }

    public function guardarturno(CrearTurnoService $service)
    {
        $this->validate([
            'profesional_id'  => 'required',
            'especialidad_id' => 'required',
            'servicio_id'     => 'required',
            'consultorio_id'  => 'required',
            'sesiones'        => 'required|integer|min:1',
            'hora'            => 'required',
            'fecha'           => 'required|date|after_or_equal:today',
        ]);

        $fechaHoraSel = Carbon::parse("{$this->fecha} {$this->hora}");
        if ($fechaHoraSel->isPast()) {
            $this->dispatch('toast', type:'error', message:'No puedes seleccionar un horario que ya pasó.');
            return;
        }

        $data = [
            'paciente_id'     => Auth::id(),
            'profesional_id'  => $this->profesional_id,
            'especialidad_id' => $this->especialidad_id,
            'servicio_id'     => $this->servicio_id,
            'fecha'           => $this->fecha,
            'hora'            => $this->hora,
            'consultorio_id'  => $this->consultorio_id,
            'sesiones'        => $this->sesiones,
        ];

        try {
            $resultado = $service->crearTurnos($data);

            $this->resumenTurnos = $resultado['resumen'];
            $this->turnoCreado   = $resultado['turnoReferencia'];

            $this->paso = 2;

        } catch (\RuntimeException $e) {
            $this->dispatch('toast', type:'error', message:$e->getMessage());
        }
    }

    public function confirmarTurno()
    {
        if (!$this->turnoCreado) {
            $this->dispatch('toast', type:'error', message:'No hay turno para confirmar.');
            return;
        }

        $this->turnoCreado->load(['paciente', 'profesional', 'servicio', 'consultorio']);

        if ($this->turnoCreado->estado_turno !== 'Confirmado') {
            $this->turnoCreado->update(['estado_turno' => 'Confirmado']);
        }

        $data = [
            'nombre'       => $this->turnoCreado->paciente->name,
            'email'        => $this->turnoCreado->paciente->email,
            'fecha'        => $this->turnoCreado->fecha_turno,
            'hora'         => $this->turnoCreado->hora_inicio_turno,
            'profesional'  => $this->turnoCreado->profesional->nombre,
            'servicio'     => $this->turnoCreado->servicio->nombre_servicio,
            'consultorio'  => $this->turnoCreado->consultorio->nombre_consultorio,
        ];

        Mail::to($data['email'])->send(new TurnoConfirmado($data));

        $this->dispatch('toast', type:'success', message:'Turno confirmado y correo enviado.');
    }

    public function volverAEditar(): void
    {
        $this->paso = 1;
        $this->hora = '';
        $this->generarFechasBloqueadas();
        $this->generarHorarios();
    }

    public function irAMisTurnos()
    {
        return redirect()->route('mis-turnos');
    }

    public function getWhatsappMessageProperty(): string
    {
        if (!$this->turnoCreado) return '';

        $service = app(\App\Livewire\Services\ConfirmarTurnoService::class);
        return $service->buildMessage($this->turnoCreado);
    }

    public function getWhatsappUrlProperty(): ?string
    {
        if (!$this->turnoCreado) return null;

        $service = app(\App\Livewire\Services\ConfirmarTurnoService::class);
        $phone = '5493705046526';
        return $service->buildWhatsappUrl($this->turnoCreado, $phone);
    }

    public function render()
    {
        return view('livewire.dashboard.reserva-widget', [
            'especialidades' => Especialidad::orderBy('nombre_especialidad')->get(),
            'profesionales'  => $this->especialidad_id
                ? Profesional::whereHas('especialidades', fn($q) =>
                        $q->where('especialidad_id', $this->especialidad_id)
                )->with('user')->get()
                : collect(),
            'servicios'      => $this->especialidad_id
                ? Servicio::where('especialidad_id', $this->especialidad_id)->get()
                : collect(),
            'consultorios'   => Consultorio::orderBy('nombre_consultorio')->get(),
            'horarios'       => $this->horarios,
        ]);
    }
}
