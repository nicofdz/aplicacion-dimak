<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Auth\Events\Registered;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Muestra un listado del recurso.
     */
    public function index(Request $request)
    {
        if ($request->view === 'trash') {
            $users = User::onlyTrashed()->get();
        } else {
            $users = User::all();
        }
        return view('users.index', compact('users'));
    }

    /**
     * Almacena un recurso recién creado en el almacenamiento.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:admin,supervisor,worker,viewer'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'must_change_password' => true,
            'is_active' => true,
        ]);

        event(new Registered($user));

        return redirect()->route('users.index')->with('success', 'Usuario creado correctamente.');
    }

    /**
     * Actualiza el recurso especificado en el almacenamiento.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'email' => ['sometimes', 'required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role' => ['sometimes', 'required', 'in:admin,supervisor,worker,viewer'],
            'is_active' => ['sometimes', 'required', 'boolean'],
        ]);

        $user->update($validated);

        return redirect()->back()->with('success', 'Usuario actualizado correctamente.');
    }

    /**
     * Elimina el recurso especificado del almacenamiento.
     */
    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->withErrors(['error' => 'No puedes eliminar tu propia cuenta.']);
        }

        $user->delete();
        return redirect()->route('users.index')->with('success', 'Usuario movido a la papelera.');
    }

    /**
     * Restaura el recurso especificado del almacenamiento.
     */
    public function restore($id)
    {
        $user = User::onlyTrashed()->findOrFail($id);
        $user->restore();
        return redirect()->route('users.index', ['view' => 'trash'])->with('success', 'Usuario restaurado correctamente.');
    }

    /**
     * Elimina permanentemente el recurso especificado del almacenamiento.
     */
    public function forceDelete($id)
    {
        $user = User::onlyTrashed()->findOrFail($id);
        $user->forceDelete();
        return redirect()->route('users.index', ['view' => 'trash'])->with('success', 'Usuario eliminado permanentemente.');
    }

    
}
