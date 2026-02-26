<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\UserActivity;

class LogUserActivity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
        */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Solo registrar si el usuario está autenticado y la respuesta es exitosa
        if ($request->user() && $response->getStatusCode() >= 200 && $response->getStatusCode() < 300) {
            $this->logActivity($request, $response);
        }

        return $response;
    }

    /**
     * Registrar la actividad del usuario
     */
    private function logActivity(Request $request, Response $response)
    {
        $method = $request->method();
        $path = $request->path();
        
        // Determinar el tipo de actividad basado en la ruta y método
        $activityType = $this->determineActivityType($method, $path);
        
        // Solo registrar ciertos tipos de actividades (no GET requests de lectura)
        if ($activityType && !in_array($method, ['GET', 'HEAD', 'OPTIONS'])) {
            UserActivity::create([
                'user_id' => $request->user()->id,
                'activity_type' => $activityType,
                'description' => $this->generateDescription($method, $path),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'metadata' => [
                    'method' => $method,
                    'path' => $path,
                    'status_code' => $response->getStatusCode(),
                    'timestamp' => now()->toDateTimeString(),
                ],
            ]);
        }
    }

    /**
     * Determinar el tipo de actividad
     */
    private function determineActivityType(string $method, string $path): ?string
    {
        if (str_contains($path, 'posts')) {
            if ($method === 'POST') return 'create_post';
            if ($method === 'PUT' || $method === 'PATCH') return 'edit_post';
            if ($method === 'DELETE') return 'delete_post';
        }

        if (str_contains($path, 'comments')) {
            if ($method === 'POST') return 'create_comment';
            if ($method === 'PUT' || $method === 'PATCH') return 'edit_comment';
            if ($method === 'DELETE') return 'delete_comment';
        }

        if (str_contains($path, 'categories')) {
            if ($method === 'POST') return 'create_category';
            if ($method === 'PUT' || $method === 'PATCH') return 'edit_category';
            if ($method === 'DELETE') return 'delete_category';
        }

        return 'api_request';
    }

    /**
     * Generar descripción de la actividad
     */
    private function generateDescription(string $method, string $path): string
    {
        return "Usuario realizó {$method} en {$path}";
    }
}
