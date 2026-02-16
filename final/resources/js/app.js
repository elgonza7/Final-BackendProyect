import './bootstrap';
const API_URL = 'http://localhost:8000/api';
async function fetchProducts() {
try {
    const response = await fetch(`${API_URL}/products`);
    if (!response.ok) {
    throw new Error('La respuesta de la red no fue correcta');
    }
    const products = await response.json();
    console.log(products); // Aquí renderizarías los productos en el HTML
    } catch (error) {
    console.error('Hubo un problema con la petición Fetch:', error);
    }
}
fetchProducts();