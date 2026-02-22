<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserActivity;
use App\Notifications\WelcomeNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Registered;

class AuthController extends Controller
{
    /**
     * Registrar un nuevo usuario
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Asignar rol de usuario por defecto
        $user->assignRole('user');

        // Disparar evento de registro (esto enviará el email de verificación)
        event(new Registered($user));

        // Enviar notificación de bienvenida
        $user->notify(new WelcomeNotification($user));

        // Registrar actividad
        $this->logActivity($user, 'register', 'Usuario registrado exitosamente', $request);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Usuario registrado exitosamente. Por favor, verifica tu email.',
            'user' => $user,
            'token' => $token,
        ], 201);
    }

    /**
     * Iniciar sesión
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Credenciales incorrectas'
            ], 401);
        }

        // Registrar actividad de login
        $this->logActivity($user, 'login', 'Usuario inició sesión', $request);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Inicio de sesión exitoso',
            'user' => $user->load('roles', 'permissions'),
            'token' => $token,
        ], 200);
    }

    /**
     * Cerrar sesión
     */
    public function logout(Request $request)
    {
        // Registrar actividad de logout
        $this->logActivity($request->user(), 'logout', 'Usuario cerró sesión', $request);

        // Eliminar el token actual
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Sesión cerrada exitosamente'
        ], 200);
    }

    /**
     * Obtener información del usuario autenticado
     */
    public function me(Request $request)
    {
        return response()->json([
            'user' => $request->user()->load('roles', 'permissions')
        ], 200);
    }

    /**
     * Verificar email
     */
    public function verifyEmail(Request $request, $id, $hash)
    {
        $user = User::findOrFail($id);

        if (!hash_equals((string) $hash, sha1($user->email))) {
            return response()->json([
                'message' => 'Link de verificación inválido'
            ], 400);
        }

        if ($user->hasVerifiedEmail()) {
            return response()->json([
                'message' => 'Email ya verificado'
            ], 200);
        }

        $user->markEmailAsVerified();

        // Registrar actividad
        $this->logActivity($user, 'email_verified', 'Usuario verificó su email', $request);

        return response()->json([
            'message' => 'Email verificado exitosamente'
        ], 200);
    }

    /**
     * Reenviar email de verificación
     */
    public function resendVerificationEmail(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return response()->json([
                'message' => 'Email ya verificado'
            ], 200);
        }

        $request->user()->sendEmailVerificationNotification();

        return response()->json([
            'message' => 'Email de verificación enviado'
        ], 200);
    }

    /**
     * Registrar actividad del usuario
     */
    private function logActivity(User $user, string $type, string $description, Request $request)
    {
        UserActivity::create([
            'user_id' => $user->id,
            'activity_type' => $type,
            'description' => $description,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'metadata' => [
                'timestamp' => now()->toDateTimeString(),
            ],
        ]);
    }
}
