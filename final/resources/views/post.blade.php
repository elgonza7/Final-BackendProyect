<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $post->title }} - Blog</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
        }
        .back-btn {
            display: inline-block;
            margin-bottom: 20px;
            padding: 10px 20px;
            background: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
            transition: background 0.3s;
        }
        .back-btn:hover {
            background: #2980b9;
        }
        .post-container {
            display: grid;
            grid-template-columns: 1fr 300px;
            gap: 20px;
        }
        .post-card {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #2c3e50;
            margin-bottom: 15px;
            font-size: 2.2em;
            line-height: 1.2;
        }
        .post-meta {
            background: #ecf0f1;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
            font-size: 0.95em;
            color: #555;
        }
        .post-meta p {
            margin: 8px 0;
        }
        .category-badges {
            margin-top: 10px;
        }
        .category-badge {
            display: inline-block;
            background: #27ae60;
            color: white;
            padding: 6px 15px;
            border-radius: 20px;
            font-size: 0.85em;
            margin: 5px 5px 5px 0;
        }
        .add-comment-btn {
            display: inline-block;
            background: #e67e22;
            color: white;
            padding: 12px 25px;
            border-radius: 4px;
            text-decoration: none;
            font-weight: bold;
            font-size: 1em;
            transition: all 0.3s ease;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }
        .add-comment-btn:hover {
            background: #d35400;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        }
        .post-content {
            font-size: 1.1em;
            line-height: 1.8;
            color: #333;
            margin-bottom: 40px;
            white-space: pre-wrap;
            word-wrap: break-word;
        }
        .sidebar {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            height: fit-content;
        }
        .sidebar h3 {
            color: #2c3e50;
            margin-bottom: 15px;
            font-size: 1.2em;
            border-bottom: 2px solid #3498db;
            padding-bottom: 10px;
        }
        .comments-section {
            margin-top: 20px;
            border-top: 2px solid #ecf0f1;
            padding-top: 20px;
        }
        .comments-section h2 {
            color: #2c3e50;
            margin-bottom: 20px;
            font-size: 1.5em;
        }
        .comment {
            background: #f8f9fa;
            padding: 15px;
            border-left: 4px solid #3498db;
            margin-bottom: 15px;
            border-radius: 4px;
            animation: slideIn 0.3s ease;
        }
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .comment-author {
            font-weight: bold;
            color: #2c3e50;
            font-size: 1.05em;
        }
        .comment-meta {
            font-size: 0.85em;
            color: #7f8c8d;
            margin-top: 5px;
        }
        .comment-content {
            margin-top: 10px;
            color: #333;
            line-height: 1.6;
            white-space: pre-wrap;
            word-wrap: break-word;
        }
        .time-ago {
            font-weight: 600;
            color: #e74c3c;
        }
        .no-comments {
            text-align: center;
            padding: 20px;
            color: #7f8c8d;
            font-style: italic;
        }
        @media (max-width: 768px) {
            .post-container {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    @include('components.navbar')
    
    <div class="container">
        <a href="/" class="back-btn">← Volver a inicio</a>
        
        <div class="post-container">
            <!-- Post Principal -->
            <div class="post-card">
                @if(session('success'))
                    <div style="background: #d4edda; border: 1px solid #c3e6cb; color: #155724; padding: 12px; border-radius: 4px; margin-bottom: 20px;">
                        ✅ {{ session('success') }}
                    </div>
                @endif
                
                <h1>{{ $post->title }}</h1>
                
                @if($post->image)
                    <div style="margin-bottom: 20px;">
                        <img src="/storage/{{ $post->image }}" alt="{{ $post->title }}" style="width: 100%; max-height: 500px; object-fit: cover; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.15);">
                    </div>
                @endif
                
                <div class="post-meta">
                    <p><strong>👤 Autor:</strong> {{ $post->user->name ?? 'Anónimo' }}</p>
                    <p><strong>📅 Fecha:</strong> <span class="time-info">{{ $post->created_at->format('d \d\e F \d\e Y') }}</span></p>
                    @if($post->categories && $post->categories->count() > 0)
                        <p><strong>🏷️ Categorías:</strong></p>
                        <div class="category-badges">
                            @foreach($post->categories as $category)
                                <span class="category-badge">{{ $category->name }}</span>
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="post-content">
{{ $post->content }}
                </div>

                <div style="margin-bottom: 40px;">
                    @auth
                        <a href="/comments/{{ $post->id }}" class="add-comment-btn">✍️ Agregar Comentario</a>
                        @if($post->user_id == auth()->id() || (auth()->user()->hasRole('admin')))
                            <button onclick="deletePost({{ $post->id }})" style="background: #e74c3c; color: white; padding: 12px 25px; border: none; border-radius: 4px; cursor: pointer; font-weight: bold; font-size: 1em; transition: all 0.3s ease; margin-left: 10px;">
                                🗑️ Eliminar Post
                            </button>
                        @endif
                    @else
                        <a href="/login" class="add-comment-btn">🔑 Inicia sesión para comentar</a>
                    @endauth
                </div>
                
                <!--comentarios -->
                @if($post->comments && $post->comments->count() > 0)
                    <div class="comments-section">
                        <h2>💬 Comentarios ({{ $post->comments->count() }})</h2>
                        
                        @foreach($post->comments as $comment)
                            <div class="comment">
                                <div class="comment-author">{{ $comment->name }}</div>
                               
                                <div class="comment-meta">
                                    👤 Por: <strong>{{ $comment->user->name ?? 'Anónimo' }}</strong> | 
                                    ⏰ <span class="time-ago">{{ $comment->created_at->diffForHumans() }}</span>
                                    @auth
                                        @if($comment->user_id == auth()->id() || auth()->user()->hasRole('admin'))
                                            <button onclick="deleteComment({{ $comment->id }})" style="background: #e74c3c; color: white; border: none; padding: 5px 10px; border-radius: 3px; cursor: pointer; font-size: 0.8em; margin-left: 10px;">
                                                🗑️ Eliminar
                                            </button>
                                        @endif
                                    @endauth
                                </div>
                                <div class="comment-content">
{{ $comment->content }}
                                </div>
                                @if($comment->image)
                                    <div style="margin-top: 10px;">
                                        <img src="/storage/{{ $comment->image }}" alt="Imagen del comentario" style="max-width: 100%; max-height: 300px; border-radius: 6px; border: 1px solid #ecf0f1; cursor: pointer;" onclick="this.style.maxHeight = this.style.maxHeight === 'none' ? '300px' : 'none'">
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="comments-section">
                        <h2>💬 Comentarios</h2>
                        <div class="no-comments">
                            No hay comentarios aún. ¡Sé el primero en comentar!
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar de Información -->
            <div class="sidebar">

                <h3>📊 Información</h3>
                <p><strong>Total de comentarios:</strong></p>
                <p style="font-size: 2em; color: #3498db; margin-bottom: 15px;">{{ $post->comments->count() ?? 0 }}</p>
                
                <p><strong>Escrito por:</strong></p>
                <p style="color: #27ae60; margin-bottom: 15px;">{{ $post->user->name ?? 'Anónimo' }}</p>
                
                <p><strong>Última actualización:</strong></p>
                <p style="color: #7f8c8d; font-size: 0.9em;">{{ $post->updated_at->diffForHumans() }}</p>
            </div>
        </div>
    </div>

    <script>
        // Función para calcular tiempo relativo de una manera más amigable
        document.addEventListener('DOMContentLoaded', function() {
            const timeElements = document.querySelectorAll('.time-ago');
            
            timeElements.forEach(el => {
                const text = el.textContent.trim();
                // Traducciones más amigables
                let friendlyText = text
                    .replace('hace', 'Hace')
                    .replace('just now', 'Justo ahora')
                    .replace('1 second ago', 'Hace 1 segundo')
                    .replace('seconds ago', 'segundos atrás')
                    .replace('1 minute ago', 'Hace 1 minuto')
                    .replace('minutes ago', 'minutos atrás')
                    .replace('1 hour ago', 'Hace 1 hora')
                    .replace('hours ago', 'horas atrás')
                    .replace('1 day ago', 'Ayer')
                    .replace('days ago', 'días atrás')
                    .replace('1 month ago', 'Hace 1 mes')
                    .replace('months ago', 'meses atrás');
                
                el.textContent = friendlyText;
            });
        });
        
        function deletePost(postId) {
            if (confirm('¿Estás seguro de que deseas eliminar este post? Esta acción no se puede deshacer.')) {
                const token = '{{ csrf_token() }}';
                fetch('/post/delete/' + postId, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': token,
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => {
                    if (response.ok) {
                        alert('Post eliminado exitosamente');
                        window.location.href = '/';
                    } else {
                        alert('Error al eliminar el post');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error al eliminar el post: ' + error.message);
                });
            }
        }
        
        function deleteComment(commentId) {
            if (confirm('¿Estás seguro de que deseas eliminar este comentario?')) {
                const token = '{{ csrf_token() }}';
                fetch('/comment/delete/' + commentId, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': token,
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => {
                    if (response.ok) {
                        alert('Comentario eliminado exitosamente');
                        location.reload();
                    } else {
                        alert('Error al eliminar el comentario');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error al eliminar el comentario: ' + error.message);
                });
            }
        }
    </script>
</body>
</html>
