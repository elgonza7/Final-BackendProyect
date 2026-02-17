<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test - Blog</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 900px;
            margin: 20px auto;
            padding: 20px;
        }
        #output {
            background: #f0f0f0;
            padding: 20px;
            border-radius: 5px;
            margin: 20px 0;
            white-space: pre-wrap;
            word-break: break-all;
        }
        .error {
            color: red;
            font-weight: bold;
        }
        .success {
            color: green;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h1>Test de Pagina Blog</h1>
    <button onclick="testAPI()">Probar API /post</button>
    <div id="output">Esperando prueba...</div>

    <script>
        async function testAPI() {
            const output = document.getElementById('output');
            output.innerHTML = '<span class="success">Iniciando prueba...</span>\n';
            
            try {
                output.innerHTML += 'Ubicacion: ' + window.location.href + '\n';
                output.innerHTML += 'Base URL esperada: ' + window.location.origin + '\n\n';
                
                output.innerHTML += 'Intentando fetch a: ' + window.location.origin + '/post\n';
                
                const response = await fetch(window.location.origin + '/post');
                output.innerHTML += '<span class="success">✓ Response recibida: ' + response.status + '</span>\n';
                
                if (!response.ok) {
                    output.innerHTML += '<span class="error">✗ Error HTTP ' + response.status + '</span>\n';
                    return;
                }
                
                const data = await response.json();
                output.innerHTML += '<span class="success">✓ JSON parseado</span>\n';
                output.innerHTML += 'Total de posts: ' + data.length + '\n\n';
                
                data.forEach((post, idx) => {
                    output.innerHTML += `Post ${idx + 1}: "${post.title}"\n`;
                    output.innerHTML += `  - ID: ${post.id}\n`;
                    output.innerHTML += `  - Comentarios: ${post.comments ? post.comments.length : 0}\n\n`;
                });
                
            } catch (error) {
                output.innerHTML += '<span class="error">✗ Error: ' + error.message + '</span>\n';
                output.innerHTML += error.stack + '\n';
            }
        }
        
        // Prueba automatica al cargar
        window.addEventListener('load', function() {
            document.getElementById('output').innerHTML = 'Script cargado correctamente. Haz click en "Probar API" para iniciar.';
        });
    </script>
</body>
</html>
