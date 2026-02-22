<nav style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 0; margin: 0; position: sticky; top: 0; z-index: 100;">
    <div style="max-width: 900px; margin: 0 auto; padding: 0 20px; display: flex; justify-content: space-between; align-items: center; height: 70px;">
        <!-- Logo/Titulo -->
        <div style="display: flex; align-items: center; gap: 20px;">
            <a href="/" style="color: white; text-decoration: none; font-size: 1.5em; font-weight: bold; display: flex; align-items: center;">
                📝 Aplicación de Posteo
            </a>
        </div>
        
        <!-- Menu derecha -->
        <div style="display: flex; align-items: center; gap: 20px;">
            @auth
                <!-- Usuario autenticado -->
                <!-- Boton Crear Post -->
                <a href="/crear-post" style="background: #e67e22; color: white; padding: 10px 20px; border-radius: 4px; text-decoration: none; font-weight: bold; transition: all 0.3s; display: inline-block;">
                    ➕ Crear Post
                </a>
                
                <!-- Dropdown Usuario -->
                <div style="position: relative; display: inline-block;">
                    <button onclick="toggleUserMenu()" style="background: white; color: #667eea; border: none; padding: 10px 15px; border-radius: 4px; cursor: pointer; font-weight: bold; display: flex; align-items: center; gap: 8px;">
                        👤 {{ auth()->user()->name }}
                        <span style="font-size: 0.8em;">▼</span>
                    </button>
                    
                    <div id="userMenu" style="display: none; position: absolute; top: 100%; right: 0; background: white; border-radius: 4px; box-shadow: 0 4px 6px rgba(0,0,0,0.2); min-width: 200px; margin-top: 5px;">
                        <a href="/mi-cuenta" style="display: block; padding: 12px 20px; color: #333; text-decoration: none; border-bottom: 1px solid #ecf0f1; transition: background 0.2s;">
                            👤 Mi Cuenta
                        </a>
                        <a href="/mis-posts" style="display: block; padding: 12px 20px; color: #333; text-decoration: none; border-bottom: 1px solid #ecf0f1; transition: background 0.2s;">
                            📰 Mis Posts
                        </a>
                        @if(auth()->user()->hasRole('admin'))
                        <a href="/admin/usuarios" style="display: block; padding: 12px 20px; color: #e67e22; text-decoration: none; border-bottom: 1px solid #ecf0f1; transition: background 0.2s; font-weight: bold;">
                            👥 Panel Usuarios
                        </a>
                        @endif
                        <a href="/logout" style="display: block; padding: 12px 20px; color: #e74c3c; text-decoration: none; transition: background 0.2s; font-weight: bold;">
                            🚪 Cerrar Sesión
                        </a>
                    </div>
                </div>
            @else
                <!-- Usuario NO autenticado -->
                <a href="/login" style="background: white; color: #667eea; padding: 10px 20px; border-radius: 4px; text-decoration: none; font-weight: bold; transition: all 0.3s; display: inline-block; border: 2px solid white;">
                    🔑 Iniciar Sesión
                </a>
                <a href="/register" style="background: #e67e22; color: white; padding: 10px 20px; border-radius: 4px; text-decoration: none; font-weight: bold; transition: all 0.3s; display: inline-block;">
                    ➕ Registrarse
                </a>
            @endauth
        </div>
    </div>
</nav>

<script>
function toggleUserMenu() {
    const menu = document.getElementById('userMenu');
    if (menu) {
        menu.style.display = menu.style.display === 'none' ? 'block' : 'none';
    }
}

// Cerrar menu al hacer click fuera
document.addEventListener('click', function(event) {
    const userMenu = document.getElementById('userMenu');
    if (userMenu) {
        const button = event.target.closest('button');
        if (!button || !button.onclick || button.onclick.toString().indexOf('toggleUserMenu') === -1) {
            userMenu.style.display = 'none';
        }
    }
});

// Hover en los items del menu
document.addEventListener('DOMContentLoaded', function() {
    const menuItems = document.querySelectorAll('#userMenu a');
    menuItems.forEach(item => {
        item.addEventListener('mouseover', function() {
            this.style.background = '#ecf0f1';
        });
        item.addEventListener('mouseout', function() {
            this.style.background = 'transparent';
        });
    });
});
</script>
