<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Comentario - {{ $post->title }}</title>
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
            max-width: 700px;
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
        .form-card {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .post-info {
            background: #ecf0f1;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 25px;
            border-left: 4px solid #3498db;
        }
        .post-info h3 {
            color: #2c3e50;
            margin-bottom: 10px;
            font-size: 0.95em;
            font-weight: bold;
        }
        .post-info p {
            color: #555;
            margin: 5px 0;
        }
        h1 {
            color: #2c3e50;
            margin-bottom: 25px;
            font-size: 1.8em;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            color: #2c3e50;
            font-weight: bold;
            margin-bottom: 8px;
        }
        input[type="text"],
        textarea {
            width: 100%;
            padding: 12px;
            border: 2px solid #ecf0f1;
            border-radius: 4px;
            font-family: inherit;
            font-size: 1em;
            transition: border-color 0.3s;
        }
        input[type="text"]:focus,
        textarea:focus {
            outline: none;
            border-color: #3498db;
            background: #f8fbff;
        }
        textarea {
            resize: vertical;
            min-height: 150px;
        }
        .button-group {
            display: flex;
            gap: 10px;
            margin-top: 30px;
        }
        button[type="submit"],
        .cancel-btn {
            flex: 1;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            font-weight: bold;
            font-size: 1em;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        button[type="submit"] {
            background: #27ae60;
            color: white;
        }
        button[type="submit"]:hover {
            background: #229954;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        .cancel-btn {
            background: #95a5a6;
            color: white;
            text-decoration: none;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .cancel-btn:hover {
            background: #7f8c8d;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        .success-message {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
            padding: 12px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    @include('components.navbar')
    
    <div class="container">
        <a href="/post/{{ $post->id }}" class="back-btn">← Volver al Post</a>
        
        <div class="form-card">
            <h1>✍️ Agregar un Comentario</h1>
            
            <!-- Información del Post -->
            <div class="post-info">
                <h3>Post: {{ $post->title }}</h3>
                <p><strong>Autor:</strong> {{ $post->user->name ?? 'Anónimo' }}</p>
                <p><strong>Fecha:</strong> {{ $post->created_at->format('d \d\e F \d\e Y') }}</p>
            </div>

            @if(session('success'))
                <div class="success-message">
                    ✅ {{ session('success') }}
                </div>
            @endif

            <!-- Formulario para agregar comentario -->
            <form action="/comment/store/{{ $post->id }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="name"><strong>Titulo Del Comentario:</strong></label>
                    <input type="text" id="name" name="name" placeholder="Ingresa el título de tu comentario" required>
                    @error('name')
                        <span style="color: #e74c3c; font-size: 0.9em;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="content"><strong>Tu Comentario:</strong></label>
                    <textarea id="content" name="content" placeholder="Escribe tu comentario aquí..." required></textarea>
                    @error('content')
                        <span style="color: #e74c3c; font-size: 0.9em;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="button-group">
                    <button type="submit">📤 Publicar Comentario</button>
                    <a href="/post/{{ $post->id }}" class="cancel-btn">❌ Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>