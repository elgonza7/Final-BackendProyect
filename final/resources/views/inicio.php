<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio</title>
</head>
<body>
    <h1 >Inicio</h1>    
    <section id="posts-section">
        <h2>Posts</h2>

        <form id="create-post-form">
            <div>
                <label for="title">Título</label><br>
                <input type="text" id="title" name="title" required>
            </div>
            <div>
                <label for="content">Contenido</label><br>
                <textarea id="content" name="content" required></textarea>
            </div>
            <button type="submit">Crear Post</button>
        </form>

        <h3>Lista de posts</h3>
        <div id="posts-list">Cargando...</div>

        <h3>Detalle</h3>
        <div id="post-detail">Selecciona un post para ver detalles.</div>
    </section>

    <meta name="csrf-token" content="<?php echo csrf_token(); ?>">

    <script>
        const postsList = document.getElementById('posts-list');
        const postDetail = document.getElementById('post-detail');
        const form = document.getElementById('create-post-form');

        async function fetchPosts() {
            try {
                const res = await fetch('/post');
                const data = await res.json();
                renderPosts(data);
            } catch (e) {
                postsList.textContent = 'Error cargando posts.';
            }
        }

        function renderPosts(posts) {
            if (!Array.isArray(posts) || posts.length === 0) {
                postsList.innerHTML = '<em>No hay posts.</em>';
                return;
            }
            postsList.innerHTML = '';
            posts.forEach(p => {
                const el = document.createElement('div');
                el.style.borderBottom = '1px solid #ddd';
                el.style.padding = '8px 0';
                el.innerHTML = `<strong>${escapeHtml(p.title)}</strong> <button data-id="${p.id}">Ver</button>`;
                const btn = el.querySelector('button');
                btn.addEventListener('click', () => fetchPost(p.id));
                postsList.appendChild(el);
            });
        }

        async function fetchPost(id) {
            try {
                const res = await fetch('/post/' + id);
                const data = await res.json();
                postDetail.innerHTML = `<h4>${escapeHtml(data.title)}</h4><p>${escapeHtml(data.content)}</p>`;
            } catch (e) {
                postDetail.textContent = 'Error cargando el post.';
            }
        }

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const title = document.getElementById('title').value.trim();
            const content = document.getElementById('content').value.trim();
            if (!title || !content) return;
            try {
                const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const res = await fetch('/post', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token
                    },
                    body: JSON.stringify({ title, content })
                });
                if (res.ok) {
                    form.reset();
                    fetchPosts();
                } else {
                    alert('Error al crear post');
                }
            } catch (e) {
                alert('Error en la petición');
            }
        });

        // small helper to avoid XSS when inserting plain text
        function escapeHtml(str) {
            if (!str) return '';
            return String(str)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/\"/g, '&quot;')
                .replace(/'/g, '&#39;');
        }

        // inicializar
        fetchPosts();
    </script>
</body>
</html>