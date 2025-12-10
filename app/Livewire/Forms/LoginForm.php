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
        $originalRun = $this->run;
        $formattedRun = \App\Helpers\RunFormatter::format($this->run);

        \Log::info('Intentando autenticar', [
            'run_original' => $originalRun,
            'run_formateado' => $formattedRun,
        ]);

        // Buscar el usuario por RUN
        $user = \App\Models\User::where('run', $formattedRun)->first();

        if (!$user) {
            \Log::warning('Usuario no encontrado (login bypass)', ['run' => $formattedRun]);
            return false;
        }

        // Verificar la contraseña proporcionada
        $cleanPassword = trim($this->password);
        $passwordValid = Hash::check($cleanPassword, $user->contrasena);

        \Log::info('Verificación de contraseña', [
            'password_valid' => $passwordValid,
            'password_length' => strlen($cleanPassword),
        ]);

        if (! $passwordValid) {
            \Log::warning('Contraseña inválida', ['run' => $formattedRun]);
            session()->flash('status', 'Credenciales inválidas.');
            return false;
        }

        // Autenticar al usuario
        Auth::login($user, $this->remember);

        // Regenerar la sesión DENTRO del formulario (antes de Livewire responda)
        // Esto asegura que la nueva sesión se cree en BD con el user_id correcto
        session()->regenerate();

        \Log::info('Usuario autenticado', [
            'run' => $user->run,
            'remember' => $this->remember,
        ]);

        return true;
    }
}
