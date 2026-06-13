<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    // ver todos los roles
    public function index()
    {
        $roles = Role::with('permissions')->get();
        return response()->json($roles);
    }

    // ver un rol especifico
    public function show($id)
    {
        $rol = Role::with('permissions')->find($id);

        if (!$rol) {
            return response()->json(['mensaje' => 'Rol no encontrado'], 404);
        }

        return response()->json($rol);
    }

    // crear un rol nuevo
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:roles,name',
        ]);

        $rol = Role::create([
            'name' => $request->name,
            'guard_name' => 'api'
        ]);

        return response()->json([
            'mensaje' => 'Rol creado correctamente',
            'rol' => $rol
        ], 201);
    }

    // asignar rol a un usuario
    public function asignarRol(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'rol' => 'required|string|exists:roles,name',
        ]);

        $usuario = \App\Models\User::find($request->user_id);
        $usuario->assignRole($request->rol);

        return response()->json([
            'mensaje' => 'Rol asignado correctamente al usuario'
        ]);
    }

    // ver que rol tiene un usuario
    public function rolesDeUsuario($id)
    {
        $usuario = \App\Models\User::find($id);

        if (!$usuario) {
            return response()->json(['mensaje' => 'Usuario no encontrado'], 404);
        }

        return response()->json([
            'usuario' => $usuario->name,
            'roles' => $usuario->getRoleNames()
        ]);
    }
}
