<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory; // permite usar factories para testing/seeding

    // Mass Assignment: campos que se pueden llenar con create() o update()
    // IMPORTANTE: solo incluir campos seguros, nunca 'id' ni timestamps
    protected $fillable = [
        'title',
        'content',
        'user_id',
        'image',
    ];

    // Casts: convierte automáticamente tipos de datos
    // 'datetime' = convierte a objeto Carbon (fácil de manipular fechas)
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // === RELACIONES ELOQUENT ===
    
    // Relación N:1 - Un post pertenece a UN usuario
    // belongsTo = foreign key está en esta tabla (posts tiene user_id)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relación 1:N - Un post tiene MUCHOS comentarios
    // hasMany = la FK está en la otra tabla (comments tiene post_id)
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
    
    // Relación N:M - Un post tiene MUCHAS categorías Y una categoría tiene MUCHOS posts
    // belongsToMany = necesita tabla pivote (post_category)
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'post_category');
    }
}
