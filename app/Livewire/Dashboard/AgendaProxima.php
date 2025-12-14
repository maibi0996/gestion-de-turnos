<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Turno;
use Carbon\Carbon;

class AgendaProxima extends Component
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
        $maniana = Carbon::tomorrow()->toDateString();

        $profesionalId = Auth::user()->profesional->id ?? null;

        $query = Turno::with(['paciente', 'profesional.user', 'servicio'])
            ->whereDate('fecha_turno', '>=', $maniana)
            ->where('estado_turno', '!=', 'cancelado')
            ->orderBy('fecha_turno')
            ->orderBy('hora_inicio_turno');

        if ($profesionalId) {
            $query->where('profesional_id', $profesionalId);
        }

        $this->turnos = $query->limit(50)->get();
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
        return view('livewire.dashboard.agenda-proxima');
    }
}
