<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Cuenta - Blog</title>
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
        .avatar-circle img {
            width: 100%; height: 100%; object-fit: cover;
        }
        .profile-info h1 {
            color: {{ $user->profile_text_color ?? '#2c3e50' }};
            margin-bottom: 20px; font-size: 2em;
        }
        .profile-details { list-style: none; margin-bottom: 20px; }
        .profile-details li {
            padding: 10px 0; border-bottom: 1px solid #ecf0f1;
            color: {{ $user->profile_text_color ?? '#555' }}; font-size: 1.05em;
        }
        .profile-details li:last-child { border-bottom: none; }
        .profile-details strong { color: {{ $user->profile_text_color ?? '#2c3e50' }}; }
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
        .stat-card:hover { transform: translateY(-5px); box-shadow: 0 6px 12px rgba(0,0,0,0.15); }
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
        .form-group { margin-bottom: 18px; }
        .form-group label {
            display: block; font-weight: bold; margin-bottom: 6px;
            color: {{ $user->profile_text_color ?? '#2c3e50' }};
        }
        .form-group input[type="text"],
        .form-group input[type="email"],
        .form-group input[type="password"] {
            width: 100%; padding: 10px; border: 2px solid #ecf0f1;
            border-radius: 4px; font-size: 1em; transition: border-color 0.3s;
        }
        .form-group input:focus {
            outline: none; border-color: {{ $user->profile_accent_color ?? '#3498db' }};
        }
        .btn-save {
            background: #27ae60; color: white; padding: 12px 25px;
            border: none; border-radius: 4px; font-weight: bold;
            font-size: 1em; cursor: pointer; transition: all 0.3s;
        }
        .btn-save:hover { background: #229954; transform: translateY(-2px); }
        /* Tabs */
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
        /* Color picker row */
        .color-row {
            display: flex; flex-wrap: wrap; gap: 15px;
            margin-bottom: 15px;
        }
        .color-item {
            display: flex; flex-direction: column; align-items: center; gap: 5px;
        }
        .color-item label {
            font-size: 0.85em; color: {{ $user->profile_text_color ?? '#555' }};
            font-weight: 600; text-align: center;
        }
        .color-item input[type="color"] {
            width: 50px; height: 50px; border: 2px solid #ecf0f1;
            border-radius: 8px; cursor: pointer; padding: 2px;
        }
        .preview-box {
            margin-top: 15px; padding: 20px; border-radius: 8px;
            border: 2px dashed #ccc; text-align: center;
        }
        .success-msg {
            background: #d4edda; border: 1px solid #c3e6cb;
            color: #155724; padding: 12px; border-radius: 4px; margin-bottom: 20px;
        }
        .empty-message {
            text-align: center; padding: 40px 20px;
            color: #7f8c8d; font-style: italic;
        }
        @media (max-width: 768px) {
            .profile-header { grid-template-columns: 1fr; }
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
            .color-row { gap: 10px; }
        }
    </style>
</head>
<body>
    @include('components.navbar')
    
    <div class="container">
        <a href="/" class="back-btn">← Volver a Inicio</a>

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
        
        <!-- Tabs -->
        <div class="tabs">
            <button class="tab-btn active" data-tab="posts">📰 Mis Posts</button>
            <button class="tab-btn" data-tab="edit">✏️ Editar Perfil</button>
            <button class="tab-btn" data-tab="customize">🎨 Personalizar</button>
        </div>

        <!-- TAB: Mis Posts -->
        <div class="section-card tab-content active" id="tab-posts">
            <h2>📰 Mis Posts ({{ $user->posts->count() }})</h2>
            @if($user->posts->count() > 0)
                @foreach($user->posts->sortByDesc('created_at') as $post)
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
                        </div>
                    </div>
                @endforeach
            @else
                <div class="empty-message">
                    📭 Aún no has creado ningún post. ¡Crea tu primer post ahora!
                </div>
            @endif
        </div>

        <!-- TAB: Editar Perfil -->
        <div class="section-card tab-content" id="tab-edit">
            <h2>✏️ Editar Perfil</h2>
            <form action="/mi-cuenta/update" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="avatar">📷 Foto de Perfil:</label>
                    <input type="file" id="avatar" name="avatar" accept="image/*" style="padding: 8px;">
                    @if($user->avatar)
                        <div style="margin-top: 8px;">
                            <img src="/storage/{{ $user->avatar }}" alt="Avatar actual" style="width: 80px; height: 80px; border-radius: 50%; object-fit: cover; border: 2px solid #ecf0f1;">
                            <span style="color: #7f8c8d; font-size: 0.85em; margin-left: 8px;">Avatar actual</span>
                        </div>
                    @endif
                    @error('avatar')
                        <span style="color: #e74c3c; font-size: 0.9em;">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="name">👤 Nombre:</label>
                    <input type="text" id="name" name="name" value="{{ $user->name }}" required>
                    @error('name')
                        <span style="color: #e74c3c; font-size: 0.9em;">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="email">📧 Email:</label>
                    <input type="email" id="email" name="email" value="{{ $user->email }}" required>
                    @error('email')
                        <span style="color: #e74c3c; font-size: 0.9em;">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="password">🔒 Nueva Contraseña (dejar vacío para no cambiar):</label>
                    <input type="password" id="password" name="password" placeholder="Nueva contraseña">
                    @error('password')
                        <span style="color: #e74c3c; font-size: 0.9em;">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="password_confirmation">🔒 Confirmar Contraseña:</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Confirmar contraseña">
                </div>
                <button type="submit" class="btn-save">💾 Guardar Cambios</button>
            </form>
        </div>

        <!-- TAB: Personalización -->
        <div class="section-card tab-content" id="tab-customize">
            <h2>🎨 Personalizar tu Página</h2>
            <p style="color: #7f8c8d; margin-bottom: 20px;">Cambiá los colores de tu perfil para hacerlo único. Los cambios se aplican al guardar.</p>
            
            <form action="/mi-cuenta/customize" method="POST">
                @csrf
                <div class="color-row">
                    <div class="color-item">
                        <label>Fondo 1</label>
                        <input type="color" name="profile_bg_color" value="{{ $user->profile_bg_color ?? '#667eea' }}" id="c_bg1">
                    </div>
                    <div class="color-item">
                        <label>Fondo 2</label>
                        <input type="color" name="profile_bg_color2" value="{{ $user->profile_bg_color2 ?? '#764ba2' }}" id="c_bg2">
                    </div>
                    <div class="color-item">
                        <label>Tarjetas</label>
                        <input type="color" name="profile_card_color" value="{{ $user->profile_card_color ?? '#ffffff' }}" id="c_card">
                    </div>
                    <div class="color-item">
                        <label>Texto</label>
                        <input type="color" name="profile_text_color" value="{{ $user->profile_text_color ?? '#2c3e50' }}" id="c_text">
                    </div>
                    <div class="color-item">
                        <label>Acento</label>
                        <input type="color" name="profile_accent_color" value="{{ $user->profile_accent_color ?? '#3498db' }}" id="c_accent">
                    </div>
                </div>

                <div class="preview-box" id="live-preview">
                    <p style="font-weight: bold; font-size: 1.1em;" id="prev-title">Vista Previa</p>
                    <p id="prev-text" style="margin-top: 5px;">Así se verá tu perfil con estos colores.</p>
                    <button type="button" style="margin-top: 10px; padding: 8px 20px; border: none; border-radius: 4px; color: white; cursor: default;" id="prev-btn">Botón de ejemplo</button>
                </div>

                <div style="margin-top: 20px;">
                    <button type="submit" class="btn-save">🎨 Guardar Personalización</button>
                    <button type="button" onclick="resetColors()" style="background: #95a5a6; color: white; padding: 12px 25px; border: none; border-radius: 4px; font-weight: bold; font-size: 1em; cursor: pointer; margin-left: 10px;">🔄 Restablecer</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Tabs
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
                document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
                this.classList.add('active');
                document.getElementById('tab-' + this.dataset.tab).classList.add('active');
            });
        });

        // Live preview
        const inputs = {
            bg1: document.getElementById('c_bg1'),
            bg2: document.getElementById('c_bg2'),
            card: document.getElementById('c_card'),
            text: document.getElementById('c_text'),
            accent: document.getElementById('c_accent')
        };
        const preview = document.getElementById('live-preview');
        const prevTitle = document.getElementById('prev-title');
        const prevText = document.getElementById('prev-text');
        const prevBtn = document.getElementById('prev-btn');

        function updatePreview() {
            preview.style.background = 'linear-gradient(135deg, ' + inputs.bg1.value + ', ' + inputs.bg2.value + ')';
            preview.style.borderColor = inputs.accent.value;
            prevTitle.style.color = inputs.text.value;
            prevText.style.color = inputs.text.value;
            prevBtn.style.background = inputs.accent.value;
        }
        Object.values(inputs).forEach(i => i.addEventListener('input', updatePreview));
        updatePreview();

        function resetColors() {
            inputs.bg1.value = '#667eea';
            inputs.bg2.value = '#764ba2';
            inputs.card.value = '#ffffff';
            inputs.text.value = '#2c3e50';
            inputs.accent.value = '#3498db';
            updatePreview();
        }
    </script>
</body>
</html>