<?php

use App\Livewire\Empleados\Create as EmpleadosCreate;
use App\Livewire\Pacientes\Index as PacientesIndex;
use App\Livewire\Pacientes\Create as PacientesCreate;
use App\Livewire\Pacientes\Show as PacientesShow;
use App\Livewire\Pacientes\Edit as PacientesEdit;
use App\Livewire\Dashboard\MisTurnos;
use Illuminate\Support\Facades\Route;
use App\Livewire\Auth\ForgotPassword;
use App\Livewire\Auth\ResetPassword;
use App\Http\Controllers\TurnoController;
use App\Models\User;

Route::redirect('/', '/login');

Route::get('/welcome', function () {
    return view('welcome');
})->middleware('auth')->name('welcome');

Route::get('/mis-turnos', MisTurnos::class)
    ->middleware('auth')
    ->name('mis-turnos');

Route::view('/ayuda', 'livewire.dashboard.ayuda')->name('ayuda');

Route::view('profile', 'profile')
    ->middleware('auth')
    ->name('profile');

Route::middleware(['auth', 'role:Super Admin,Profesional'])->group(function () {

    Route::view('dashboard', 'dashboard')->name('dashboard');

    Route::get('/pacientes', PacientesIndex::class)->name('pacientes.index');
    Route::get('/pacientes/nuevo', PacientesCreate::class)->name('pacientes.create');

    Route::get('/pacientes/{paciente}', PacientesShow::class)->name('pacientes.show');
    Route::get('/pacientes/{paciente}/editar', PacientesEdit::class)->name('pacientes.edit');

    Route::delete('/pacientes/{paciente}', function (User $paciente) {
        $paciente->delete();
        return back()->with('success', 'Paciente eliminado correctamente.');
    })->name('pacientes.destroy');

    Route::get('/turnos/create', [TurnoController::class, 'create'])->name('turnos.create');
    Route::post('/turnos', [TurnoController::class, 'store'])->name('turnos.store');

    Route::get('/turnos/{id}/edit', [TurnoController::class, 'edit'])->name('turnos.edit');
    Route::put('/turnos/{id}', [TurnoController::class, 'update'])->name('turnos.update');

    Route::post('/turnos/{id}/confirmar', [TurnoController::class, 'confirmar'])->name('turnos.confirmar');
    Route::post('/turnos/{id}/cancelar', [TurnoController::class, 'cancelar'])->name('turnos.cancelar');
});

Route::middleware(['auth', 'role:Super Admin'])->group(function () {
    Route::get('/empleados/nuevo', EmpleadosCreate::class)->name('empleados.create');
});

Route::get('/forgot-password', ForgotPassword::class)->name('password.request');
Route::post('/forgot-password', [ForgotPassword::class, 'sendResetLink'])->name('password.email');

Route::get('/reset-password/{token}', ResetPassword::class)->name('password.reset');
Route::post('/reset-password', [ResetPassword::class, 'reset'])->name('password.update');

require __DIR__ . '/auth.php';
