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
        if ($request->user() && $response->getStatusCode() >= 200 && $response->getStatusCode() < 300) {
            $this->touchPresence($request, $response);
            $this->logActivity($request, $response);
        }

        return $response;
    }
    private function touchPresence(Request $request, Response $response): void
    {
        UserActivity::updateOrCreate(
            [
                'user_id' => $request->user()->id,
                'activity_type' => 'presence_ping',
            ],
            [
                'description' => 'Usuario en línea',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'metadata' => [
                    'method' => $request->method(),
                    'path' => $request->path(),
                    'status_code' => $response->getStatusCode(),
                    'timestamp' => now()->toDateTimeString(),
                ],
            ]
        );
    }

    private function logActivity(Request $request, Response $response): void
    {
        $method = $request->method();
        $path = $request->path();
        $activityType = $this->determineActivityType($method, $path);

        if (!$activityType || in_array($method, ['GET', 'HEAD', 'OPTIONS'])) {
            return;
        }

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

    private function determineActivityType(string $method, string $path): ?string
    {
        if (str_contains($path, 'post')) {
            if ($method === 'POST') return 'create_post';
            if ($method === 'PUT' || $method === 'PATCH') return 'edit_post';
            if ($method === 'DELETE') return 'delete_post';
        }

        if (str_contains($path, 'comment')) {
            if ($method === 'POST') return 'create_comment';
            if ($method === 'PUT' || $method === 'PATCH') return 'edit_comment';
            if ($method === 'DELETE') return 'delete_comment';
        }

        if (str_contains($path, 'categor')) {
            if ($method === 'POST') return 'create_category';
            if ($method === 'PUT' || $method === 'PATCH') return 'edit_category';
            if ($method === 'DELETE') return 'delete_category';
        }

        if (str_contains($path, 'login') && $method === 'POST') return 'login';
        if (str_contains($path, 'logout') && $method === 'POST') return 'logout';
        if (str_contains($path, 'register') && $method === 'POST') return 'register';

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
