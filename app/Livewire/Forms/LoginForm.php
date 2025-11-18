<?php
namespace App\Livewire\Forms;
use App\Rules\RunValidation;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Validate;
use Livewire\Form;
class LoginForm extends Form
{
    #[Validate('required|string')]
    public string $run = '';
    #[Validate('required|string')]
    public string $password = '';
    #[Validate('boolean')]
    public bool $remember = false;
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();
        
        // Formatear RUN antes de buscar
        $originalRun = $this->run;
        $this->run = \App\Helpers\RunFormatter::format($this->run);
        
        \Log::info('Intentando autenticar', [
            'run_original' => $originalRun,
            'run_formateado' => $this->run,
            'password_length' => strlen($this->password),
        ]);
        
        // Buscar el usuario por RUN
        $user = \App\Models\User::where('run', $this->run)->first();
        
        if (!$user) {
            \Log::warning('Usuario no encontrado', ['run' => $this->run]);
            RateLimiter::hit($this->throttleKey());
            throw ValidationException::withMessages([
                'form.run' => trans('auth.failed'),
            ]);
        }
        
        \Log::info('Usuario encontrado', [
            'run' => $user->run,
            'nombre' => $user->nombre,
            'password_hash_preview' => substr($user->contrasena, 0, 20) . '...',
        ]);
        
        // Verificar credenciales manualmente
        // Limpiar espacios en la contraseña
        $cleanPassword = trim($this->password);
        $passwordValid = \Illuminate\Support\Facades\Hash::check($cleanPassword, $user->contrasena);
        
        \Log::info('Verificación de contraseña', [
            'password_valid' => $passwordValid,
            'password_provided' => $this->password,
            'password_provided_length' => strlen($this->password),
            'password_clean' => $cleanPassword,
            'password_clean_length' => strlen($cleanPassword),
            'password_has_spaces' => $this->password !== $cleanPassword,
            'hash_preview' => substr($user->contrasena, 0, 30),
            'hash_length' => strlen($user->contrasena),
        ]);
        
        if (!$passwordValid) {
            \Log::warning('Contraseña inválida', ['run' => $this->run]);
            RateLimiter::hit($this->throttleKey());
            throw ValidationException::withMessages([
                'form.run' => trans('auth.failed'),
            ]);
        }
        
        // Autenticar al usuario manualmente
        Auth::login($user, $this->remember);
        RateLimiter::clear($this->throttleKey());
        
        \Log::info('Usuario autenticado exitosamente', [
            'run' => $user->run,
            'remember' => $this->remember,
        ]);
    }
    protected function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }
        event(new Lockout(request()));
        $seconds = RateLimiter::availableIn($this->throttleKey());
        throw ValidationException::withMessages([
            'form.run' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }
    protected function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->run).'|'.request()->ip());
    }
}
