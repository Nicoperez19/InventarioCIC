<?php
namespace App\Http\Controllers;
use App\Models\User;
use App\Rules\RunValidation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
class AuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'run' => ['required', 'string', new RunValidation()],
                'contrasena' => 'required|string'
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Datos de validación incorrectos',
                    'errors' => $validator->errors()
                ], 422);
            }
            $run = \App\Helpers\RunFormatter::format($request->run);
            $user = User::where('run', $run)->first();
            if (!$user || !Hash::check($request->contrasena, $user->contrasena)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Credenciales incorrectas'
                ], 401);
            }
            $token = $user->createToken('auth-token')->plainTextToken;
            return response()->json([
                'success' => true,
                'data' => [
                    'user' => $user->load(['departamento', 'permissions']),
                    'token' => $token
                ],
                'message' => 'Login exitoso'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error en el login: ' . $e->getMessage()
            ], 500);
        }
    }
    public function logout(Request $request): JsonResponse
    {
        try {
            $request->user()->currentAccessToken()->delete();
            return response()->json([
                'success' => true,
                'message' => 'Logout exitoso'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error en el logout: ' . $e->getMessage()
            ], 500);
        }
    }
    public function me(Request $request): JsonResponse
    {
        try {
            $user = $request->user()->load(['departamento', 'permissions', 'roles']);
            return response()->json([
                'success' => true,
                'data' => $user,
                'message' => 'Usuario obtenido exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener usuario: ' . $e->getMessage()
            ], 500);
        }
    }
}
