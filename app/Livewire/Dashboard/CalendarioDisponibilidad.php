<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use Carbon\Carbon;
use App\Models\DisponibilidadProfesional;
use App\Models\Turno;
use Illuminate\Support\Facades\Auth;

class CalendarioDisponibilidad extends Component
{
    public $mes;
    public $anio;
    public $diasHabilitados = [];
    public $diasConTurnos = [];
    public $fechaSeleccionada = null;
    public $horarios = [];
    public $mensajeDia = null;
    public $pacientesPorDia = [];


    protected $diasSemana = [
        1 => 'Lunes',
        2 => 'Martes',
        3 => 'Miércoles',
        4 => 'Jueves',
        5 => 'Viernes',
        6 => 'Sábado',
        7 => 'Domingo',
    ];

    public function mount()
    {
        $this->mes  = now()->month;
        $this->anio = now()->year;

        $this->cargarDisponibilidad();
        $this->cargarDiasConTurnos();
    }

    protected function cargarDisponibilidad()
    {
        $profesional = Auth::user()->profesional ?? null;
        if (!$profesional) return;

        $this->diasHabilitados = DisponibilidadProfesional::where('profesional_id', $profesional->id)
            ->pluck('dia_semana')
            ->unique()
            ->toArray();
    }

    protected function cargarDiasConTurnos()
    {
        $profesional = Auth::user()->profesional ?? null;
        if (!$profesional) return;

        $turnos = Turno::with('paciente')
            ->where('profesional_id', $profesional->id)
            ->whereMonth('fecha_turno', $this->mes)
            ->whereYear('fecha_turno', $this->anio)
            ->where('estado_turno', '!=', 'cancelado')
            ->get();

        foreach ($turnos as $turno) {
            $fecha = \Carbon\Carbon::parse($turno->fecha_turno)->toDateString();
            $this->pacientesPorDia[$fecha][] = [
                'nombre' => $turno->paciente->name,
                'hora'   => substr($turno->hora_inicio_turno, 0, 5),
            ];
        }

        $this->diasConTurnos = array_keys($this->pacientesPorDia);
    }
    public function seleccionarDia($fecha)
    {
        $this->fechaSeleccionada = $fecha;
        $this->mensajeDia = null;

        $profesional = Auth::user()->profesional ?? null;
        if (!$profesional) return;

        $fechaCarbon = \Carbon\Carbon::parse($fecha);
        $diaTexto = ucfirst($fechaCarbon->locale('es')->dayName);


        if (!in_array($diaTexto, $this->diasHabilitados)) {
            $this->mensajeDia = "Día no laboral";
            return;
        }
    }

    protected function cargarHorarios()
    {
        $this->horarios = [];

        $profesional = Auth::user()->profesional ?? null;
        if (!$profesional || !$this->fechaSeleccionada) return;

        $fecha = Carbon::parse($this->fechaSeleccionada);
        $diaTexto = $this->diasSemana[$fecha->dayOfWeekIso];

        $disponibilidades = DisponibilidadProfesional::where('profesional_id', $profesional->id)
            ->where('dia_semana', $diaTexto)
            ->get();

        foreach ($disponibilidades as $disp) {
            $desde = Carbon::createFromFormat('H:i:s', $disp->hora_inicio);
            $hasta = Carbon::createFromFormat('H:i:s', $disp->hora_fin);

            while ($desde < $hasta) {

                $ocupado = Turno::where('profesional_id', $profesional->id)
                    ->whereDate('fecha_turno', $fecha->toDateString())
                    ->whereTime('hora_inicio_turno', '<', $desde->format('H:i:s'))
                    ->whereTime('hora_fin_turno', '>', $desde->format('H:i:s'))
                    ->where('estado_turno', '!=', 'cancelado')
                    ->exists();

                $this->horarios[] = [
                    'hora' => $desde->format('H:i'),
                    'estado' => $ocupado ? 'ocupado' : 'disponible',
                ];

                $desde->addMinutes(30);
            }
        }
    }

    public function render()
    {
        $inicioMes = Carbon::create($this->anio, $this->mes, 1);
        return view('livewire.dashboard.calendario-disponibilidad', [
            'inicio' => $inicioMes->copy()->startOfWeek(Carbon::MONDAY),
            'fin' => $inicioMes->copy()->endOfMonth()->endOfWeek(Carbon::SUNDAY),
        ]);
    }
}