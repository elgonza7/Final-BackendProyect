<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio - Blog</title>
    <script src="inicio.js"></script>
    <link rel="stylesheet" href="inicio.css">

    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background-color: #f5f5f5;
            color: #333;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
            padding: 20px;
        }
        .post-card {
            background: white;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .post-card h3 {
            margin-top: 0;
            color: #2c3e50;
            border-bottom: 2px solid #3498db;
            padding-bottom: 10px;
        }
        .post-meta {
            color: #7f8c8d;
            font-size: 0.9em;
            margin: 10px 0;
        }
        .comments-section {
            background: #ecf0f1;
            padding: 15px;
            border-radius: 5px;
            margin-top: 15px;
        }
        .comment {
            background: white;
            padding: 10px;
            margin-bottom: 10px;
            border-left: 3px solid #3498db;
            border-radius: 3px;
        }
        .comment-author {
            font-weight: bold;
            color: #2c3e50;
        }
        .comment-date {
            color: #95a5a6;
            font-size: 0.85em;
        }
        .form-section {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .form-section input, .form-section textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #bdc3c7;
            border-radius: 4px;
            font-family: inherit;
        }
        .form-section textarea {
            min-height: 100px;
            resize: vertical;
        }
        .form-section button {
            background: #3498db;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1em;
        }
        .form-section button:hover {
            background: #2980b9;
        }
        .btn {
            background: #27ae60;
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.9em;
            margin-top: 10px;
        }
        .btn:hover {
            background: #229954;
        }
        #posts-list {
            margin-top: 20px;
        }
        .loading {
            text-align: center;
            color: #7f8c8d;
            font-size: 1.1em;
        }
        h1 {
            color: #2c3e50;
            border-bottom: 3px solid #3498db;
            padding-bottom: 10px;
        }
        h2 {
            color: #34495e;
            margin-top: 30px;
        }

    </style>
</head>
<body>
    @include('components.navbar')
    
    <div class="container">
        <h1>📝 Blog - Inicio</h1>
        
        <section id="posts-section">
            <!-- Lista de posts -->
            <h2>📰 Lista de Posts</h2>
            <div id="posts-list" class="loading">Cargando posts...</div>
        </section>

    </div>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const postsList = document.getElementById('posts-list');

            function fetchPosts() {
                const baseUrl = window.location.origin;
                const url = baseUrl + '/post';
                
                fetch(url)
                    .then(response => response.json())
                    .then(data => renderPosts(data))
                    .catch(error => {
                        console.error('Error:', error);
                        postsList.innerHTML = '<div style="color: red;">Error cargando posts: ' + error.message + '</div>';
                    });
            }

            function renderPosts(posts) {
                if (!Array.isArray(posts) || posts.length === 0) {
                    postsList.innerHTML = '<p>No hay posts disponibles.</p>';
                    return;
                }
                
                postsList.innerHTML = '';
                
                posts.forEach(p => {
                    // Crear contenedor del post
                    const postContainer = document.createElement('div');
                    postContainer.className = 'post-card';
                    
                    // Formatear fecha
                    const postDate = new Date(p.created_at).toLocaleDateString('es-ES', {
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                    });
                    
                    // Crear HTML del post
                    let postHTML = '<h3>' + escapeHtml(p.title) + '</h3>';
                    postHTML += '<div class="post-meta">';
                    postHTML += 'Por: ' + escapeHtml(p.user?.name || 'Anónimo') + ' | ' + postDate;
                    postHTML += '</div>';
                    postHTML += '<p>' + escapeHtml(p.content) + '</p>';
                    postHTML += '<a href="/post/' + p.id + '" class="btn" style="display: inline-block; text-decoration: none;">Ver detalles completos</a>';
                    
                    postContainer.innerHTML = postHTML;
                    postsList.appendChild(postContainer);
                });
            }

            // Helper para escapar HTML
            function escapeHtml(str) {
                if (!str) return '';
                return String(str)
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/\"/g, '&quot;')
                    .replace(/'/g, '&#39;');
            }

            // Iniciar
            fetchPosts();
        });
    </script>
</body>
</html>