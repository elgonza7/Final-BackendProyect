<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;  // para autenticación API con tokens
use Spatie\Permission\Traits\HasRoles;  // sistema de roles y permisos

// MustVerifyEmail = obliga a verificar email antes de usar la app
class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    // HasApiTokens = permite generar tokens para API REST
    // HasRoles = métodos como hasRole(), givePermissionTo(), etc.
    // Notifiable = permite enviar notificaciones (email, slack, etc)
    use HasApiTokens, HasFactory, Notifiable, HasRoles;
    
    // Campos que se pueden asignar masivamente
    // Importante: password se hashea automáticamente por el cast 'hashed'
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',                   // foto de perfil
        'profile_bg_color',         // personalización: color fondo
        'profile_bg_color2',        // personalización: color fondo 2 (gradiente)
        'profile_card_color',       // personalización: color tarjetas
        'profile_text_color',       // personalización: color texto
        'profile_accent_color',     // personalización: color acentos
    ];

    // Ocultar estos campos cuando se convierte el modelo a JSON/Array
    // Seguridad: nunca exponer password o tokens en APIs
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Casts: conversión automática de tipos
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',  // hashea automáticamente al asignar
        ];
    }
    
    // === RELACIONES ===
    
    // Relación 1:N - Un usuario tiene MUCHOS posts
    public function posts()
    {
        return $this->hasMany(Post::class);
    }
    
    // Relación 1:N - Un usuario tiene MUCHOS comentarios
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
    
    // Relación 1:N - Un usuario tiene MUCHAS actividades (log de acciones)
    public function activities()
    {
        return $this->hasMany(UserActivity::class);
    }
}
