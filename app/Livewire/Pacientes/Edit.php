<?php

namespace App\Livewire\Pacientes;

use App\Models\User;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Edit extends Component
{
    public User $paciente;

    public $name;
    public $dni;
    public $phone;
    public $email;
    // public $tipo;
    // public $metodo_pago;

    public function mount(User $paciente)
    {
        $this->paciente     = $paciente;
        $this->name         = $paciente->name;
        $this->dni          = $paciente->dni;
        $this->phone        = $paciente->phone;
        $this->email        = $paciente->email;
        // $this->tipo         = $paciente->tipo;
        // $this->metodo_pago  = $paciente->metodo_pago;
    }

    public function rules()
    {
        return [
            'name'        => ['required', 'string', 'max:255'],
            'dni'         => ['nullable', 'string', 'max:20'],
            'phone'       => ['nullable', 'string', 'max:20'],
            'email'       => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($this->paciente->id),
            ],
            // 'tipo'        => ['nullable', 'string', 'max:50'],
            // 'metodo_pago' => ['nullable', 'string', 'max:50'],
        ];
    }

    public function save()
    {
        $this->validate();

        $this->paciente->name        = $this->name;
        $this->paciente->dni         = $this->dni;
        $this->paciente->phone       = $this->phone;
        $this->paciente->email       = $this->email;
        // $this->paciente->tipo        = $this->tipo;
        // $this->paciente->metodo_pago = $this->metodo_pago;

        $this->paciente->save();

        session()->flash('success', 'Paciente actualizado correctamente.');

        return redirect()->route('pacientes.index');
    }

    public function render()
    {
        return view('livewire.pacientes.edit');
    }
}
