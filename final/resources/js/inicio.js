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