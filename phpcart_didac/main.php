<?php
// Inicializar ejecución
echo "INIT EXECUTION <br>";
echo "<br>";

// Incluir los archivos necesarios para el funcionamiento
require_once "com/users/users.php"; 
require_once "com/catalog/catalog.php"; 
require_once "com/cart/cart.php"; 
require_once "com/utils/delete_items.php"; 

// Registro de un nuevo usuario 'didac'
RegisterUser('didac', 'didac');

// Añadir productos al catálogo
AddToCatalog(1, 'Oreo', 10, 10);
AddToCatalog(2, 'Actimel', 10, 10);
AddToCatalog(3, 'Granizado', 10, 10);
AddToCatalog(4, 'Limonada', 10, 10);

// Mostrar todos los productos del catálogo
echo "<h2>Catálogo de productos disponibles:</h2>";
ShowCatalog();

// Añadir un producto al carrito del usuario 'didac'
echo "<h2>Carrito de compras de 'didac':</h2>";
AddToCart('didac', 1, 'Oreo', 10, 10);

// Mostrar contenido del carrito del usuario con descuento aplicado
ShowCart('didac', 'DESC10');

?>
