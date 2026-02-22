<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio - Blog</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f0f2f5;
            color: #333;
        }
        .container {
            max-width: 960px;
            margin: 0 auto;
            padding: 20px;
        }
        h1 {
            color: #2c3e50;
            border-bottom: 3px solid #3498db;
            padding-bottom: 10px;
            margin-bottom: 25px;
        }
        /* Category Filter */
        .category-filter {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 25px;
            padding: 15px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.08);
            align-items: center;
        }
        .category-filter label {
            font-weight: bold;
            color: #2c3e50;
            margin-right: 5px;
        }
        .filter-btn {
            padding: 8px 18px;
            border: 2px solid #3498db;
            border-radius: 20px;
            background: white;
            color: #3498db;
            cursor: pointer;
            font-weight: 600;
            font-size: 0.9em;
            transition: all 0.3s;
        }
        .filter-btn:hover, .filter-btn.active {
            background: #3498db;
            color: white;
        }
        /* Post cards */
        .posts-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
        }
        .post-card {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            transition: transform 0.3s, box-shadow 0.3s;
            display: flex;
            flex-direction: column;
        }
        .post-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.15);
        }
        .post-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        .post-image-placeholder {
            width: 100%;
            height: 200px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3em;
            color: rgba(255,255,255,0.5);
        }
        .post-body {
            padding: 20px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        .post-card h3 {
            margin: 0 0 10px;
            color: #2c3e50;
            font-size: 1.15em;
            line-height: 1.3;
        }
        .post-card h3 a {
            text-decoration: none;
            color: inherit;
        }
        .post-card h3 a:hover {
            color: #3498db;
        }
        .post-excerpt {
            color: #666;
            font-size: 0.92em;
            line-height: 1.5;
            margin-bottom: 12px;
            flex: 1;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .post-meta {
            color: #95a5a6;
            font-size: 0.85em;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 10px;
            border-top: 1px solid #ecf0f1;
        }
        .post-author {
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .post-author img {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            object-fit: cover;
        }
        .post-categories {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
            margin-bottom: 10px;
        }
        .cat-badge {
            display: inline-block;
            background: #e8f5e9;
            color: #27ae60;
            padding: 3px 10px;
            border-radius: 12px;
            font-size: 0.78em;
            font-weight: 600;
        }
        .btn-details {
            display: inline-block;
            background: #3498db;
            color: white;
            padding: 8px 16px;
            border-radius: 4px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.88em;
            transition: background 0.3s;
            text-align: center;
            margin-top: 10px;
        }
        .btn-details:hover { background: #2980b9; }
        .loading {
            text-align: center;
            color: #7f8c8d;
            font-size: 1.1em;
            padding: 40px;
        }
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #95a5a6;
        }
        .empty-state p { font-size: 1.1em; margin-top: 10px; }
        @media (max-width: 600px) {
            .posts-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    @include('components.navbar')
    
    <div class="container">
        <h1>📝 Blog - Publicaciones Recientes</h1>
        
        <!-- Filtro de categorías -->
        <div class="category-filter">
            <label>🏷️ Filtrar:</label>
            <button class="filter-btn active" data-category="">Todas</button>
            @foreach(\App\Models\Category::all() as $category)
                <button class="filter-btn" data-category="{{ $category->id }}">{{ $category->name }}</button>
            @endforeach
        </div>

        <div id="posts-list" class="loading">Cargando posts...</div>
    </div>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const postsList = document.getElementById('posts-list');
            let activeCategory = '';

            document.querySelectorAll('.filter-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                    activeCategory = this.dataset.category;
                    fetchPosts();
                });
            });

            function fetchPosts() {
                postsList.innerHTML = '<div class="loading">Cargando posts...</div>';
                const baseUrl = window.location.origin;
                let url = baseUrl + '/post';
                if (activeCategory) {
                    url += '?category=' + activeCategory;
                }
                
                fetch(url)
                    .then(response => response.json())
                    .then(data => renderPosts(data))
                    .catch(error => {
                        console.error('Error:', error);
                        postsList.innerHTML = '<div style="color: red; text-align: center; padding: 20px;">Error cargando posts: ' + error.message + '</div>';
                    });
            }

            function renderPosts(posts) {
                if (!Array.isArray(posts) || posts.length === 0) {
                    postsList.innerHTML = '<div class="empty-state">📭<p>No hay posts disponibles en esta categoría.</p></div>';
                    return;
                }
                
                let html = '<div class="posts-grid">';
                
                posts.forEach(p => {
                    const postDate = new Date(p.created_at).toLocaleDateString('es-ES', {
                        year: 'numeric', month: 'short', day: 'numeric'
                    });

                    let imageHTML = '';
                    if (p.image) {
                        imageHTML = '<img class="post-image" src="/storage/' + escapeHtml(p.image) + '" alt="' + escapeHtml(p.title) + '">';
                    } else {
                        imageHTML = '<div class="post-image-placeholder">📝</div>';
                    }

                    let categoriesHTML = '';
                    if (p.categories && p.categories.length > 0) {
                        categoriesHTML = '<div class="post-categories">';
                        p.categories.forEach(c => {
                            categoriesHTML += '<span class="cat-badge">' + escapeHtml(c.name) + '</span>';
                        });
                        categoriesHTML += '</div>';
                    }

                    let avatarHTML = '';
                    if (p.user && p.user.avatar) {
                        avatarHTML = '<img src="/storage/' + escapeHtml(p.user.avatar) + '" alt="">';
                    }

                    html += '<div class="post-card">';
                    html += imageHTML;
                    html += '<div class="post-body">';
                    html += '<h3><a href="/post/' + p.id + '">' + escapeHtml(p.title) + '</a></h3>';
                    html += categoriesHTML;
                    html += '<p class="post-excerpt">' + escapeHtml(p.content) + '</p>';
                    html += '<div class="post-meta">';
                    html += '<span class="post-author">' + avatarHTML + ' ' + escapeHtml(p.user?.name || 'Anónimo') + '</span>';
                    html += '<span>' + postDate + '</span>';
                    html += '</div>';
                    html += '<a href="/post/' + p.id + '" class="btn-details">Ver detalles →</a>';
                    html += '</div>';
                    html += '</div>';
                });
                
                html += '</div>';
                postsList.innerHTML = html;
            }

            function escapeHtml(str) {
                if (!str) return '';
                return String(str)
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;')
                    .replace(/'/g, '&#39;');
            }

            fetchPosts();
        });
    </script>
</body>
</html>
