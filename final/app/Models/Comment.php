<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',      // título del comentario
        'content',   // contenido del comentario
        'post_id',   // FK: a qué post pertenece
        'user_id',   // FK: quién escribió el comentario
        'image',     // imagen opcional
    ];

    // Auto-convertir a Carbon para manipular fechas fácilmente
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relación N:1 - Un comentario pertenece a UN usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    // Relación N:1 - Un comentario pertenece a UN post
    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
