<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    // Campos que se pueden asignar masivamente
    protected $fillable = [
        'name',
        'slug', // slug para URLs amigables (ej: /categoria/tecnologia)
    ];

    // Relación N:M - Una categoría tiene MUCHOS posts
    // belongsToMany = tabla pivote post_category conecta categories con posts
    // Nota: el orden de las tablas en el nombre es alfabético: post_category (no category_post)
    public function posts()
    {
        return $this->belongsToMany(Post::class, 'post_category');
    }
}
