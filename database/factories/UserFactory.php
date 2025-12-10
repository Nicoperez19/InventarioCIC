<?php
namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Departamento;
class UserFactory extends Factory
{
    protected static ?string $password;
    public function definition(): array
    {
        return [
            'run' => fake()->unique()->numerify('########-#'),
            'nombre' => fake()->name(),
            'correo' => fake()->unique()->safeEmail(),
            'correo_verificado_at' => now(),
            'contrasena' => static::$password ??= Hash::make('password'),
            'id_depto' => function () {
                // Asegurar que exista un departamento por defecto en tests y factories
                $depto = Departamento::firstOrCreate(
                    ['id_depto' => 'CIC_info'],
                    ['nombre_depto' => 'CIC Información']
                );
                return $depto->id_depto;
            },
            'remember_token' => Str::random(10),
        ];
    }
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'correo_verificado_at' => null,
        ]);
    }
}
