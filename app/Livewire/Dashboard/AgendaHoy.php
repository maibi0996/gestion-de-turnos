<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Turno;
use Carbon\Carbon;

class AgendaHoy extends Component
{
    public $turnos = [];

    protected $listeners = [
        'turnoConfirmado' => 'cargarTurnos',
        'turnoActualizado' => 'cargarTurnos'
    ];

    public function mount()
    {
        $this->cargarTurnos();
    }

    public function cargarTurnos()
    {
        $hoy = Carbon::today()->toDateString();

        $profesionalId = Auth::user()->profesional->id ?? null;

        $query = Turno::with(['paciente', 'profesional.user', 'servicio'])
            ->whereDate('fecha_turno', $hoy)
            ->where('estado_turno', '!=', 'cancelado')
            ->orderBy('hora_inicio_turno');

        if ($profesionalId) {
            $query->where('profesional_id', $profesionalId);
        }

        $this->turnos = $query->get();
    }

    public function confirmarTurno($id)
    {
        $turno = Turno::find($id);
        if (!$turno) return;

        $turno->estado_turno = 'confirmado';
        $turno->save();

        $this->cargarTurnos();

        $this->emit('turnoConfirmado', $id);
        session()->flash('success', 'Turno confirmado correctamente.');
    }

    public function render()
    {
        return view('livewire.dashboard.agenda-hoy');
    }
}
