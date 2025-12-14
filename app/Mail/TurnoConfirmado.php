<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TurnoConfirmado extends Mailable
{
    use Queueable, SerializesModels;

    public $data; //

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function build()
    {
        return $this->subject('Confirmación de Turno')
                    ->view('emails.turno-confirmado')
                    ->with($this->data);
    }
}