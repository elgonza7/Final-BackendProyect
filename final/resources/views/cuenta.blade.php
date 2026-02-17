<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Cuenta - Blog</title>
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
        .profile-header {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 30px;
            align-items: center;
        }
        .profile-avatar {
            text-align: center;
        }
        .avatar-circle {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 60px;
            color: white;
            margin: 0 auto;
        }
        .profile-info h1 {
            color: #2c3e50;
            margin-bottom: 20px;
            font-size: 2em;
        }
        .profile-details {
            list-style: none;
            margin-bottom: 20px;
        }
        .profile-details li {
            padding: 10px 0;
            border-bottom: 1px solid #ecf0f1;
            color: #555;
            font-size: 1.05em;
        }
        .profile-details li:last-child {
            border-bottom: none;
        }
        .profile-details strong {
            color: #2c3e50;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
            transition: transform 0.3s ease;
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }
        .stat-number {
            font-size: 2.5em;
            font-weight: bold;
            color: #3498db;
            margin-bottom: 10px;
        }
        .stat-label {
            color: #7f8c8d;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 0.9em;
        }
        .posts-section {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .posts-section h2 {
            color: #2c3e50;
            margin-bottom: 20px;
            font-size: 1.5em;
            border-bottom: 2px solid #3498db;
            padding-bottom: 10px;
        }
        .post-item {
            background: #f8f9fa;
            padding: 20px;
            margin-bottom: 15px;
            border-left: 4px solid #3498db;
            border-radius: 4px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: all 0.3s ease;
        }
        .post-item:hover {
            background: #ecf0f1;
            transform: translateX(5px);
        }
        .post-content {
            flex: 1;
        }
        .post-title {
            font-weight: bold;
            color: #2c3e50;
            font-size: 1.1em;
            margin-bottom: 5px;
        }
        .post-meta {
            color: #7f8c8d;
            font-size: 0.9em;
        }
        .post-actions {
            display: flex;
            gap: 10px;
        }
        .btn {
            padding: 8px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
            font-size: 0.9em;
        }
        .btn-view {
            background: #3498db;
            color: white;
        }
        .btn-view:hover {
            background: #2980b9;
        }
        .btn-delete {
            background: #e74c3c;
            color: white;
        }
        .btn-delete:hover {
            background: #c0392b;
        }
        .empty-message {
            text-align: center;
            padding: 40px 20px;
            color: #7f8c8d;
            font-style: italic;
        }
        @media (max-width: 768px) {
            .profile-header {
                grid-template-columns: 1fr;
            }
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>
</head>
<body>
    @include('components.navbar')
    
    <div class="container">
        <a href="/" class="back-btn">← Volver a Inicio</a>
        
        <!-- Perfil del Usuario -->
        <div class="profile-header">
            <div class="profile-avatar">
                <div class="avatar-circle">👤</div>
            </div>
            <div class="profile-info">
                <h1>{{ $user->name }}</h1>
                <ul class="profile-details">
                    <li><strong>📧 Email:</strong> {{ $user->email }}</li>
                    <li><strong>📝 Posts creados:</strong> {{ $user->posts->count() }}</li>
                    <li><strong>💬 Comentarios realizados:</strong> {{ $user->comments->count() }}</li>
                    <li><strong>📅 Miembro desde:</strong> {{ $user->created_at->format('d \d\e F \d\e Y') }}</li>
                </ul>
            </div>
        </div>
        
        <!-- Estadísticas -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number">{{ $user->posts->count() }}</div>
                <div class="stat-label">Posts Publicados</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ $user->comments->count() }}</div>
                <div class="stat-label">Comentarios</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ $totalComentsReceived }}</div>
                <div class="stat-label">Comentarios en tus Posts</div>
            </div>
        </div>
        
        <!-- Mis Posts -->
        <div class="posts-section">
            <h2>📰 Mis Posts ({{ $user->posts->count() }})</h2>
            
            @if($user->posts->count() > 0)
                @foreach($user->posts as $post)
                    <div class="post-item">
                        <div class="post-content">
                            <div class="post-title">{{ $post->title }}</div>
                            <div class="post-meta">
                                Publicado: {{ $post->created_at->format('d \d\e F \d\e Y') }} | 
                                Comentarios: {{ $post->comments->count() }}
                            </div>
                        </div>
                        <div class="post-actions">
                            <a href="/post/{{ $post->id }}" class="btn btn-view">👁️ Ver</a>
                            <button onclick="editPost({{ $post->id }})" class="btn btn-view" style="background: #f39c12;">✏️ Editar</button>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="empty-message">
                    📭 Aún no has creado ningún post. ¡Crea tu primer post ahora!
                </div>
            @endif
        </div>
    </div>
    
    <script>
        function editPost(postId) {
            alert('Función de editar en desarrollo');
        }
    </script>
</body>
</html>
