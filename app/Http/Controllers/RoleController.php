<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::all();
        return response()->json($roles);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
        ]);

        $role = Role::create($validatedData);

        return response()->json([
            'message' => 'Role created successfully',
            'role' => $role,
        ]);
    }

    public function show($id)
    {
        $role = Role::findOrFail($id);
        return response()->json($role);
    }

    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,'.$id,
        ]);

        $role->update($validatedData);

        return response()->json([
            'message' => 'Role updated successfully',
            'role' => $role,
        ]);
    }

    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();

        return response()->json([
            'message' => 'Role deleted successfully',
        ]);
    }
}
