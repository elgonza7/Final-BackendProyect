document.addEventListener('DOMContentLoaded', function() {
    const postsList = document.getElementById('posts-list');
    const postDetail = document.getElementById('post-detail');
    const form = document.getElementById('create-post-form');

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
            postHTML += '<button class="btn" data-id="' + p.id + '">Ver detalles completos</button>';
            
            // Agregar comentarios
            if (p.comments && p.comments.length > 0) {
                postHTML += '<div class="comments-section">';
                postHTML += '<h4>Comentarios (' + p.comments.length + '):</h4>';
                
                p.comments.forEach(c => {
                    const commentDate = new Date(c.created_at).toLocaleDateString('es-ES', {
                        year: 'numeric',
                        month: 'short',
                        day: 'numeric'
                    });
                    
                    postHTML += '<div class="comment">';
                    postHTML += '<div class="comment-author">' + escapeHtml(c.name) + '</div>';
                    postHTML += '<small style="color: #7f8c8d;">Por: ' + escapeHtml(c.user?.name || 'Anónimo') + '</small>';
                    postHTML += '<p style="margin: 8px 0;">' + escapeHtml(c.content) + '</p>';
                    postHTML += '<div class="comment-date">' + commentDate + '</div>';
                    postHTML += '</div>';
                });
                
                postHTML += '</div>';
            } else {
                postHTML += '<div class="comments-section"><em>Sin comentarios aún</em></div>';
            }
            
            postContainer.innerHTML = postHTML;
            
            // Agregar evento al botón
            const btn = postContainer.querySelector('button');
            if (btn) {
                btn.addEventListener('click', function() {
                    fetchPost(p.id);
                });
            }
            
            postsList.appendChild(postContainer);
        });
    }

    function fetchPost(id) {
        const baseUrl = window.location.origin;
        const url = baseUrl + '/post/' + id;
        
        fetch(url)
            .then(response => response.json())
            .then(data => {
                const postDate = new Date(data.created_at).toLocaleDateString('es-ES', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });
                
                let detailHTML = '<h3>' + escapeHtml(data.title) + '</h3>';
                detailHTML += '<div class="post-meta">';
                detailHTML += 'Por: ' + escapeHtml(data.user?.name || 'Anónimo') + ' | ' + postDate;
                detailHTML += '</div>';
                detailHTML += '<p>' + escapeHtml(data.content) + '</p>';
                
                if (data.comments && data.comments.length > 0) {
                    detailHTML += '<h4>Comentarios (' + data.comments.length + '):</h4>';
                    data.comments.forEach(c => {
                        detailHTML += '<div class="comment">';
                        detailHTML += '<div class="comment-author">' + escapeHtml(c.name) + '</div>';
                        detailHTML += '<small>Por: ' + escapeHtml(c.user?.name || 'Anónimo') + '</small>';
                        detailHTML += '<p>' + escapeHtml(c.content) + '</p>';
                        detailHTML += '</div>';
                    });
                }
                
                postDetail.innerHTML = detailHTML;
            })
            .catch(error => {
                console.error('Error:', error);
                postDetail.innerHTML = '<div style="color: red;">Error al cargar el post: ' + error.message + '</div>';
            });
    }

    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const title = document.getElementById('title').value.trim();
            const content = document.getElementById('content').value.trim();
            
            if (!title || !content) {
                alert('Por favor completa todos los campos');
                return;
            }
            
            const baseUrl = window.location.origin;
            const url = baseUrl + '/post';
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token
                },
                body: JSON.stringify({ title, content, user_id: 1 })
            })
            .then(response => response.json())
            .then(data => {
                alert('Post creado exitosamente');
                form.reset();
                fetchPosts();
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al crear el post: ' + error.message);
            });
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