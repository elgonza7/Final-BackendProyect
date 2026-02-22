<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuarios - Blog</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f0f2f5;
            color: #333;
        }
        .container { max-width: 960px; margin: 0 auto; padding: 20px; }
        h1 {
            color: #2c3e50;
            border-bottom: 3px solid #3498db;
            padding-bottom: 10px;
            margin-bottom: 25px;
        }
        .back-btn {
            display: inline-block; margin-bottom: 20px; padding: 10px 20px;
            background: #3498db; color: white;
            text-decoration: none; border-radius: 4px; font-weight: bold; transition: all 0.3s;
        }
        .back-btn:hover { background: #2980b9; }
        .users-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
        }
        .user-card {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            transition: transform 0.3s, box-shadow 0.3s;
            padding: 25px;
            text-align: center;
        }
        .user-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.15);
        }
        .user-avatar {
            width: 80px; height: 80px; border-radius: 50%;
            margin: 0 auto 15px; display: flex;
            align-items: center; justify-content: center;
            font-size: 2em; color: white;
            overflow: hidden;
            border: 3px solid #3498db;
        }
        .user-avatar img {
            width: 100%; height: 100%; object-fit: cover;
        }
        .user-name {
            font-size: 1.2em; font-weight: bold; color: #2c3e50; margin-bottom: 5px;
        }
        .user-email {
            color: #7f8c8d; font-size: 0.9em; margin-bottom: 12px;
        }
        .user-role {
            display: inline-block; padding: 4px 12px; border-radius: 12px;
            font-size: 0.8em; font-weight: 600; margin-bottom: 12px;
        }
        .role-admin { background: #e74c3c; color: white; }
        .role-user { background: #3498db; color: white; }
        .role-editor { background: #27ae60; color: white; }
        .role-moderator { background: #e67e22; color: white; }
        .user-stats {
            display: flex; justify-content: center; gap: 20px;
            margin: 12px 0; padding: 10px 0;
            border-top: 1px solid #ecf0f1; border-bottom: 1px solid #ecf0f1;
        }
        .user-stat { text-align: center; }
        .user-stat-number { font-weight: bold; color: #2c3e50; font-size: 1.2em; }
        .user-stat-label { font-size: 0.75em; color: #7f8c8d; }
        .btn-profile {
            display: inline-block; padding: 8px 20px;
            background: #3498db; color: white; border-radius: 4px;
            text-decoration: none; font-weight: 600; font-size: 0.9em;
            transition: background 0.3s; margin-top: 10px;
        }
        .btn-profile:hover { background: #2980b9; }
        .member-since {
            font-size: 0.8em; color: #95a5a6; margin-top: 8px;
        }
    </style>
</head>
<body>
    @include('components.navbar')

    <div class="container">
        <a href="/" class="back-btn">← Volver a Inicio</a>
        <h1>👥 Usuarios ({{ $users->count() }})</h1>

        <div class="users-grid">
            @foreach($users as $user)
                <div class="user-card">
                    <div class="user-avatar" style="background: linear-gradient(135deg, {{ $user->profile_bg_color ?? '#667eea' }}, {{ $user->profile_bg_color2 ?? '#764ba2' }});">
                        @if($user->avatar)
                            <img src="/storage/{{ $user->avatar }}" alt="{{ $user->name }}">
                        @else
                            👤
                        @endif
                    </div>
                    <div class="user-name">{{ $user->name }}</div>
                    <div class="user-email">{{ $user->email }}</div>
                    @foreach($user->roles as $role)
                        <span class="user-role role-{{ $role->name }}">{{ ucfirst($role->name) }}</span>
                    @endforeach
                    <div class="user-stats">
                        <div class="user-stat">
                            <div class="user-stat-number">{{ $user->posts_count }}</div>
                            <div class="user-stat-label">Posts</div>
                        </div>
                        <div class="user-stat">
                            <div class="user-stat-number">{{ $user->comments_count }}</div>
                            <div class="user-stat-label">Comentarios</div>
                        </div>
                    </div>
                    <a href="/usuario/{{ $user->id }}" class="btn-profile">Ver Perfil →</a>
                    <div class="member-since">📅 Desde {{ $user->created_at->format('d/m/Y') }}</div>
                </div>
            @endforeach
        </div>
    </div>
</body>
</html>
