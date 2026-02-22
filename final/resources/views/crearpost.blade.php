<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Nuevo Post - Blog</title>
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
        .form-card {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #2c3e50;
            margin-bottom: 25px;
            font-size: 2em;
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
            min-height: 250px;
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
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .cancel-btn:hover {
            background: #7f8c8d;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
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
        .error-message {
            background: #fadbd8;
            border: 1px solid #f5b7b1;
            color: #c0392b;
            padding: 12px;
            border-radius: 4px;
            margin-bottom: 20px;
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
        <a href="/" class="back-btn">← Volver a Inicio</a>
        
        <div class="form-card">
            <h1>✏️ Crear Nuevo Post</h1>
            
            @if(session('success'))
                <div class="success-message">
                    ✅ {{ session('success') }}
                </div>
            @endif
            
            @if($errors->any())
                <div class="error-message">
                    <strong>⚠️ Errores en el formulario:</strong>
                    <ul style="margin-top: 10px; padding-left: 20px;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <form action="/post/crear" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="title"><strong>Título del Post:</strong></label>
                    <input 
                        type="text" 
                        id="title" 
                        name="title" 
                        placeholder="Ingresa el título de tu post" 
                        value="{{ old('title') }}"
                        required>
                    @error('title')
                        <span style="color: #e74c3c; font-size: 0.9em;">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="content"><strong>Contenido del Post:</strong></label>
                    <textarea 
                        id="content" 
                        name="content" 
                        placeholder="Escribe el contenido de tu post aquí..."
                        required>{{ old('content') }}</textarea>
                    @error('content')
                        <span style="color: #e74c3c; font-size: 0.9em;">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="image"><strong>📷 Imagen del Post (opcional):</strong></label>
                    <input type="file" id="image" name="image" accept="image/*" style="padding: 8px;">
                    <div id="image-preview" style="margin-top: 10px; display: none;">
                        <img id="preview-img" src="" alt="Preview" style="max-width: 100%; max-height: 300px; border-radius: 8px; border: 2px solid #ecf0f1;">
                    </div>
                    @error('image')
                        <span style="color: #e74c3c; font-size: 0.9em;">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label><strong>🏷️ Categorías:</strong></label>
                    <div style="display: flex; flex-wrap: wrap; gap: 10px; margin-top: 5px;">
                        @foreach(\App\Models\Category::all() as $category)
                            <label style="display: flex; align-items: center; gap: 5px; padding: 6px 14px; background: #f0f0f0; border-radius: 20px; cursor: pointer; font-weight: normal; font-size: 0.9em;">
                                <input type="checkbox" name="categories[]" value="{{ $category->id }}" style="width: auto;">
                                {{ $category->name }}
                            </label>
                        @endforeach
                    </div>
                </div>
                
                <div class="button-group">
                    <button type="submit">📝 Crear Post</button>
                    <a href="/" class="cancel-btn">❌ Cancelar</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('image').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const preview = document.getElementById('image-preview');
            const previewImg = document.getElementById('preview-img');
            if (file) {
                const reader = new FileReader();
                reader.onload = function(ev) {
                    previewImg.src = ev.target.result;
                    preview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            } else {
                preview.style.display = 'none';
            }
        });
    </script>
</body>
</html>
