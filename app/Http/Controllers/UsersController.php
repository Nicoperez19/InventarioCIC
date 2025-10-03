<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UsersController extends Controller
{
    public function edit(User $user): View
    {
        return view('layouts.user.user_update', compact('user'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'password' => ['nullable', 'string', 'min:8'],
        ]);

        if (!empty($validated['password'])) {
            $user->update($validated);
        } else {
            unset($validated['password']);
            $user->update($validated);
        }

        return redirect()->route('user-index')->with('status', 'Usuario actualizado correctamente.');
    }

    public function destroy(User $user): RedirectResponse
    {
        $user->delete();

        return redirect()->back()->with('status', 'Usuario eliminado correctamente.');
    }
}


