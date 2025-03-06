<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Role::withCount('users')->get();
        return view('admin.roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.roles.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles',
            'description' => 'nullable|string',
        ]);
        
        // Générer le slug à partir du nom
        $validated['slug'] = Str::slug($validated['name']);
        
        Role::create($validated);
        
        return redirect()->route('roles.index')
            ->with('success', 'Rôle créé avec succès.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function show(Role $role)
    {
        $role->load('users');
        return view('admin.roles.show', compact('role'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $role)
    {
        return view('admin.roles.edit', compact('role'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('roles')->ignore($role->id),
            ],
            'description' => 'nullable|string',
        ]);
        
        // Mettre à jour le slug uniquement si le nom a changé
        if ($role->name !== $validated['name']) {
            $validated['slug'] = Str::slug($validated['name']);
        }
        
        $role->update($validated);
        
        return redirect()->route('roles.index')
            ->with('success', 'Rôle mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        // Vérifier si des utilisateurs sont associés à ce rôle
        if ($role->users()->count() > 0) {
            return redirect()->route('roles.index')
                ->with('error', 'Impossible de supprimer ce rôle car il est assigné à un ou plusieurs utilisateurs.');
        }
        
        // Ne pas permettre la suppression des rôles essentiels du système
        if (in_array($role->slug, ['admin', 'gestionnaire', 'agent', 'lecteur'])) {
            return redirect()->route('roles.index')
                ->with('error', 'Les rôles système ne peuvent pas être supprimés.');
        }
        
        $role->delete();
        
        return redirect()->route('roles.index')
            ->with('success', 'Rôle supprimé avec succès.');
    }
}