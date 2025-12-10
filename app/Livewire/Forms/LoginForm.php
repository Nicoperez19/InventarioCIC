<?php
namespace App\Livewire\Forms;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Form;

class LoginForm extends Form
{
    public string $run = '';

    public string $password = '';

    public bool $remember = false;

    public function authenticate(): bool
    {
        // Formatear RUN antes de buscar
        $formattedRun = \App\Helpers\RunFormatter::format($this->run);

        // Buscar el usuario por RUN
        $user = \App\Models\User::where('run', $formattedRun)->first();

        if (!$user) {
            return false;
        }

        // Verificar la contraseña proporcionada
        $cleanPassword = trim($this->password);
        $passwordValid = Hash::check($cleanPassword, $user->contrasena);

        if (! $passwordValid) {
            session()->flash('status', 'Credenciales inválidas.');
            return false;
        }

        // Autenticar al usuario
        Auth::login($user, $this->remember);

        // Regenerar la sesión DENTRO del formulario (antes de Livewire responda)
        // Esto asegura que la nueva sesión se cree en BD con el user_id correcto
        session()->regenerate();

        return true;
    }
}
