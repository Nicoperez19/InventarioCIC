<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Departamento;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;

class UsersController extends Controller
{
    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->middleware('auth');
        $this->userService = $userService;
    }

    public function create(): View
    {
        $this->authorizeAction('create-users');

        $departamentos = Departamento::orderByName()->get();
        $permissions = Permission::orderBy('name')->get();

        return view('layouts.user.user_create', compact('departamentos', 'permissions'));
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        $this->authorizeAction('create-users');

        try {
            $this->logAction('Creando usuario', ['run' => $request->run]);

            $validated = $request->validated();
            $validated['permissions'] = $request->input('permissions', []);

            $user = $this->userService->createUser($validated);

            $this->logAction('Usuario creado exitosamente', ['user_run' => $user->run]);

            return redirect()->route('users')->with('status', 'Usuario creado exitosamente.');

        } catch (\Throwable $e) {
            return $this->handleException($e, 'UsersController@store', ['run' => $request->run]);
        }
    }

    public function edit(User $user): View
    {
        $this->authorizeAction('edit-users');

        $user->load(['permissions', 'departamento']);
        $departamentos = Departamento::orderByName()->get();
        $permissions = Permission::orderBy('name')->get();

        return view('layouts.user.user_update', compact('user', 'departamentos', 'permissions'));
    }

    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $this->authorizeAction('edit-users');

        try {
            $this->logAction('Actualizando usuario', ['user_run' => $user->run]);

            $validated = $request->validated();
            $validated['permissions'] = $request->input('permissions', []);

            $this->userService->updateUser($user, $validated);

            $this->logAction('Usuario actualizado exitosamente', ['user_run' => $user->run]);

            return redirect()->route('users')->with('status', 'Usuario actualizado exitosamente.');

        } catch (\Throwable $e) {
            return $this->handleException($e, 'UsersController@update', ['user_run' => $user->run]);
        }
    }

    public function destroy(User $user): RedirectResponse
    {
        $this->authorizeAction('delete-users');

        try {
            $this->logAction('Eliminando usuario', ['user_run' => $user->run]);

            $success = $this->userService->deleteUser($user);

            if ($success) {
                $this->logAction('Usuario eliminado exitosamente', ['user_run' => $user->run]);
                return redirect()->back()->with('status', 'Usuario eliminado exitosamente.');
            } else {
                return redirect()->back()->with('error', 'Error al eliminar el usuario.');
            }

        } catch (\Throwable $e) {
            return $this->handleException($e, 'UsersController@destroy', ['user_run' => $user->run]);
        }
    }
}
