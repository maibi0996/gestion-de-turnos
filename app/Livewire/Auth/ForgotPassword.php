<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Password;

class ForgotPassword extends Component
{
    public string $email = '';

    public function sendResetLink()
    {
        $this->validate([
            'email' => ['required', 'email'],
        ]);

        $status = Password::sendResetLink([
            'email' => $this->email
        ]);

        if ($status === Password::RESET_LINK_SENT) {
            session()->flash('message', 'Te enviamos un enlace para restablecer tu contraseña.');
        } else {
            session()->flash('error', 'No pudimos enviar el enlace. Verifica tu correo.');
        }
    }

    public function render()
    {
        return view('livewire.auth.forgot-password');
    }
}