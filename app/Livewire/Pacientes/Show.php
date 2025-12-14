<?php

namespace App\Livewire\Pacientes;

use App\Models\User;
use Livewire\Component;

class Show extends Component
{
    public User $paciente;

    public function mount(User $paciente)
    {
        $this->paciente = $paciente;
    }

    public function render()
    {
        return view('livewire.pacientes.show');
    }
}
