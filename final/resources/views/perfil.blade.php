<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de {{ $user->name }} - Blog</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, {{ $user->profile_bg_color ?? '#667eea' }} 0%, {{ $user->profile_bg_color2 ?? '#764ba2' }} 100%);
            min-height: 100vh;
            padding: 20px;
        }
        .container { max-width: 900px; margin: 0 auto; }
        .back-btn {
            display: inline-block; margin-bottom: 20px; padding: 10px 20px;
            background: {{ $user->profile_accent_color ?? '#3498db' }}; color: white;
            text-decoration: none; border-radius: 4px; font-weight: bold; transition: all 0.3s;
        }
        .back-btn:hover { opacity: 0.85; }
        .profile-header {
            background: {{ $user->profile_card_color ?? '#ffffff' }};
            padding: 30px; border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            margin-bottom: 30px;
            display: grid; grid-template-columns: 1fr 2fr;
            gap: 30px; align-items: center;
        }
        .profile-avatar { text-align: center; }
        .avatar-circle {
            width: 150px; height: 150px; border-radius: 50%;
            background: linear-gradient(135deg, {{ $user->profile_bg_color ?? '#667eea' }} 0%, {{ $user->profile_bg_color2 ?? '#764ba2' }} 100%);
            display: flex; align-items: center; justify-content: center;
            font-size: 60px; color: white; margin: 0 auto;
            overflow: hidden; border: 4px solid {{ $user->profile_accent_color ?? '#3498db' }};
        }
        .avatar-circle img { width: 100%; height: 100%; object-fit: cover; }
        .profile-info h1 {
            color: {{ $user->profile_text_color ?? '#2c3e50' }};
            margin-bottom: 10px; font-size: 2em;
        }
        .profile-details { list-style: none; margin-bottom: 10px; }
        .profile-details li {
            padding: 8px 0; border-bottom: 1px solid #ecf0f1;
            color: {{ $user->profile_text_color ?? '#555' }}; font-size: 1.05em;
        }
        .profile-details li:last-child { border-bottom: none; }
        .profile-details strong { color: {{ $user->profile_text_color ?? '#2c3e50' }}; }
        .role-badge {
            display: inline-block; padding: 4px 12px; border-radius: 12px;
            font-size: 0.8em; font-weight: 600; margin-left: 10px;
        }
        .role-admin { background: #e74c3c; color: white; }
        .role-user { background: #3498db; color: white; }
        .role-editor { background: #27ae60; color: white; }
        .role-moderator { background: #e67e22; color: white; }
        .stats-grid {
            display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px; margin-bottom: 30px;
        }
        .stat-card {
            background: {{ $user->profile_card_color ?? '#ffffff' }};
            padding: 25px; border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            text-align: center; transition: transform 0.3s ease;
        }
        .stat-card:hover { transform: translateY(-5px); }
        .stat-number {
            font-size: 2.5em; font-weight: bold;
            color: {{ $user->profile_accent_color ?? '#3498db' }};
            margin-bottom: 10px;
        }
        .stat-label { color: #7f8c8d; font-weight: bold; text-transform: uppercase; font-size: 0.9em; }
        .section-card {
            background: {{ $user->profile_card_color ?? '#ffffff' }};
            padding: 30px; border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        .section-card h2 {
            color: {{ $user->profile_text_color ?? '#2c3e50' }};
            margin-bottom: 20px; font-size: 1.5em;
            border-bottom: 2px solid {{ $user->profile_accent_color ?? '#3498db' }};
            padding-bottom: 10px;
        }
        .post-item {
            background: #f8f9fa; padding: 20px; margin-bottom: 15px;
            border-left: 4px solid {{ $user->profile_accent_color ?? '#3498db' }};
            border-radius: 4px; display: flex; justify-content: space-between;
            align-items: center; transition: all 0.3s ease;
        }
        .post-item:hover { background: #ecf0f1; transform: translateX(5px); }
        .post-content { flex: 1; }
        .post-title { font-weight: bold; color: #2c3e50; font-size: 1.1em; margin-bottom: 5px; }
        .post-meta { color: #7f8c8d; font-size: 0.9em; }
        .post-actions { display: flex; gap: 10px; }
        .btn {
            padding: 8px 15px; border: none; border-radius: 4px;
            cursor: pointer; font-weight: bold; text-decoration: none;
            display: inline-block; transition: all 0.3s ease; font-size: 0.9em;
        }
        .btn-view { background: {{ $user->profile_accent_color ?? '#3498db' }}; color: white; }
        .btn-view:hover { opacity: 0.85; }
        .btn-delete { background: #e74c3c; color: white; }
        .btn-delete:hover { background: #c0392b; }
        .comment-item {
            background: #f8f9fa; padding: 15px; margin-bottom: 10px;
            border-left: 3px solid #7f8c8d; border-radius: 4px;
        }
        .comment-item .comment-post {
            font-size: 0.85em; color: #7f8c8d; margin-bottom: 5px;
        }
        .comment-item .comment-body {
            color: #2c3e50; font-size: 0.95em;
        }
        .comment-item .comment-date {
            font-size: 0.8em; color: #95a5a6; margin-top: 5px;
        }
        .empty-message {
            text-align: center; padding: 40px 20px;
            color: #7f8c8d; font-style: italic;
        }
        .admin-badge {
            background: #e74c3c; color: white;
            padding: 5px 12px; border-radius: 4px;
            font-size: 0.8em; font-weight: bold;
        }
        .success-msg {
            background: #d4edda; border: 1px solid #c3e6cb;
            color: #155724; padding: 12px; border-radius: 4px; margin-bottom: 20px;
        }
        .tabs {
            display: flex; gap: 5px; margin-bottom: 0;
            border-bottom: 2px solid {{ $user->profile_accent_color ?? '#3498db' }};
        }
        .tab-btn {
            padding: 12px 20px; border: none; background: transparent;
            cursor: pointer; font-weight: bold; font-size: 0.95em;
            color: #7f8c8d; border-radius: 6px 6px 0 0; transition: all 0.3s;
        }
        .tab-btn.active {
            background: {{ $user->profile_card_color ?? '#ffffff' }};
            color: {{ $user->profile_accent_color ?? '#3498db' }};
        }
        .tab-content { display: none; }
        .tab-content.active { display: block; }
        @media (max-width: 768px) {
            .profile-header { grid-template-columns: 1fr; }
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
        }
    </style>
</head>
<body>
    @include('components.navbar')

    <div class="container">
        @if(auth()->check() && auth()->user()->hasRole('admin'))
            <a href="/admin/usuarios" class="back-btn">← Volver a Usuarios</a>
        @else
            <a href="/" class="back-btn">← Volver a Inicio</a>
        @endif

        @if(session('success'))
            <div class="success-msg">✅ {{ session('success') }}</div>
        @endif

        <!-- Perfil del Usuario -->
        <div class="profile-header">
            <div class="profile-avatar">
                <div class="avatar-circle">
                    @if($user->avatar)
                        <img src="/storage/{{ $user->avatar }}" alt="{{ $user->name }}">
                    @else
                        👤
                    @endif
                </div>
            </div>
            <div class="profile-info">
                <h1>
                    {{ $user->name }}
                    @foreach($user->roles as $role)
                        <span class="role-badge role-{{ $role->name }}">{{ ucfirst($role->name) }}</span>
                    @endforeach
                </h1>
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
                <div class="stat-label">Comentarios Recibidos</div>
            </div>
        </div>

        <!-- Tabs -->
        <div class="tabs">
            <button class="tab-btn active" data-tab="posts">📰 Posts ({{ $user->posts->count() }})</button>
            <button class="tab-btn" data-tab="comments">💬 Comentarios ({{ $user->comments->count() }})</button>
        </div>

        <!-- TAB: Posts del Usuario -->
        <div class="section-card tab-content active" id="tab-posts">
            <h2>📰 Posts de {{ $user->name }}</h2>
            @if($user->posts->count() > 0)
                @foreach($user->posts->sortByDesc('created_at') as $post)
                    <div class="post-item">
                        <div class="post-content">
                            <div class="post-title">{{ $post->title }}</div>
                            <div class="post-meta">
                                Publicado: {{ $post->created_at->format('d/m/Y H:i') }} |
                                Comentarios: {{ $post->comments->count() }}
                            </div>
                        </div>
                        <div class="post-actions">
                            <a href="/post/{{ $post->id }}" class="btn btn-view">👁️ Ver</a>
                            @auth
                                @if(auth()->user()->hasRole('admin'))
                                    <form action="/post/delete/{{ $post->id }}" method="POST" style="display:inline;" onsubmit="return confirm('¿Eliminar este post?')">
                                        @csrf
                                        <button type="submit" class="btn btn-delete">🗑️</button>
                                    </form>
                                @endif
                            @endauth
                        </div>
                    </div>
                @endforeach
            @else
                <div class="empty-message">📭 Este usuario no tiene posts publicados.</div>
            @endif
        </div>

        <!-- TAB: Comentarios del Usuario -->
        <div class="section-card tab-content" id="tab-comments">
            <h2>💬 Comentarios de {{ $user->name }}</h2>
            @if($user->comments->count() > 0)
                @foreach($user->comments->sortByDesc('created_at') as $comment)
                    <div class="comment-item">
                        <div class="comment-post">
                            En post: <a href="/post/{{ $comment->post_id }}" style="color: {{ $user->profile_accent_color ?? '#3498db' }}; font-weight: bold;">
                                {{ $comment->post->title ?? 'Post eliminado' }}
                            </a>
                        </div>
                        <div class="comment-body">{{ $comment->content }}</div>
                        <div class="comment-date" style="display: flex; justify-content: space-between; align-items: center;">
                            <span>{{ $comment->created_at->format('d/m/Y H:i') }}</span>
                            @auth
                                @if(auth()->user()->hasRole('admin'))
                                    <form action="/comment/delete/{{ $comment->id }}" method="POST" style="display:inline;" onsubmit="return confirm('¿Eliminar este comentario?')">
                                        @csrf
                                        <button type="submit" class="btn btn-delete" style="padding: 4px 10px; font-size: 0.8em;">🗑️ Eliminar</button>
                                    </form>
                                @endif
                            @endauth
                        </div>
                    </div>
                @endforeach
            @else
                <div class="empty-message">💬 Este usuario no tiene comentarios.</div>
            @endif
        </div>
    </div>

    <script>
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
                document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
                this.classList.add('active');
                document.getElementById('tab-' + this.dataset.tab).classList.add('active');
            });
        });
    </script>
</body>
</html>
