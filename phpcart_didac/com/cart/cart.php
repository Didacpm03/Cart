<?php
session_start(); // Iniciar la sesión

///////////////////////////////////////////////////////////////////
// Función para registrar un usuario
function RegisterUser($username, $password) {
    $file = '../users/users.xml'; // Corregir la ruta de users.xml
    if (file_exists($file)) {
        $users = simplexml_load_file($file);
    } else {
        // Si no existe el archivo, creamos uno nuevo
        $users = new SimpleXMLElement('<users></users>');
    }

    // Verificar si el usuario ya existe
    foreach ($users->user as $user) {
        if ($user->username == $username) {
            echo "El usuario ya está registrado.<br>";
            return;
        }
    }

    // Si no existe, agregar el nuevo usuario
    $newUser = $users->addChild('user');
    $newUser->addChild('username', $username);
    $newUser->addChild('password', $password);

    // Guardar el archivo XML
    $users->asXML($file);
    echo "Usuario registrado exitosamente.<br>";
}

///////////////////////////////////////////////////////////////////
// Función para iniciar sesión
function LoginUser($username, $password) {
    $file = '../users/users.xml'; // Corregir la ruta de users.xml
    if (file_exists($file)) {
        $users = simplexml_load_file($file);
    } else {
        echo "Error: No se encontró el archivo de usuarios.<br>";
        return false;
    }

    // Verificar credenciales
    foreach ($users->user as $user) {
        if ($user->username == $username && $user->password == $password) {
            $_SESSION['username'] = $username;
            echo "Inicio de sesión exitoso.<br>";
            return true;
        }
    }
    echo "Credenciales inválidas.<br>";
    return false;
}

///////////////////////////////////////////////////////////////////
// Función para añadir productos al carrito
function AddToCart($username, $id_prod, $name, $quantity, $price) {
    echo "Has añadido el producto con el ID $id_prod al carrito de $username<br>";
    
    // Verificar si el directorio 'xmldb/' existe, si no, crearlo
    $directory = 'xmldb/';
    if (!is_dir($directory)) {
        mkdir($directory, 0777, true); // Crear el directorio con permisos
    }

    $cart = LoadUserCart($username);

    $item = $cart->addChild('product_item');
    $item->addChild('id_product', $id_prod);
    $item->addChild('name', $name);
    $item->addChild('quantity', $quantity);
    $item->addChild('price', $price);

    // Guardar el carrito
    $cart->asXML($directory . $username . '_cart.xml');
}

/////////////////////////////////////////////////////
// Función para cargar el carrito del usuario
function LoadUserCart($username) {
    $file = 'xmldb/' . $username . '_cart.xml';
    if (file_exists($file)) {
        return simplexml_load_file($file);
    } else {
        return new SimpleXMLElement('<cart></cart>');
    }
}

/////////////////////////////////////////////////////
// Función para mostrar el carrito
function ShowCart($username, $discountCode = '') {
    $cart = LoadUserCart($username);
    $totalPrice = 0;
    $discount = 0;

    if ($discountCode == 'DESC10') {
        $discount = 0.10;
    } elseif ($discountCode == 'DESC20') {
        $discount = 0.20;
    }

    if ($cart->product_item->count() == 0) {
        echo "<br><strong>El carrito está vacío.</strong><br>";
    } else {
        echo "<table border='1' cellpadding='10' cellspacing='0'>";
        echo "<tr>
                <th>ID Producto</th>
                <th>Nombre</th>
                <th>Cantidad</th>
                <th>Precio Unitario</th>
              </tr>";

        foreach ($cart->product_item as $item) {
            $productTotal = (float)$item->price * (int)$item->quantity;
            $totalPrice += $productTotal;

            echo "<tr>";
            echo "<td>" . $item->id_product . "</td>";
            echo "<td>" . $item->name . "</td>";
            echo "<td>" . $item->quantity . "</td>";
            echo "<td>" . $item->price . " €</td>";
            echo "</tr>";
        }

        echo "</table>";

        echo "<br><strong>Total de la compra: " . $totalPrice . " €</strong><br>";

        if ($discount > 0) {
            $totalWithDiscount = $totalPrice - ($totalPrice * $discount);
            echo "<strong>Descuento aplicado (" . ($discount * 100) . "%): -" . ($totalPrice * $discount) . " €</strong><br>";
            echo "<strong>Total con descuento: " . $totalWithDiscount . " €</strong><br>";
        }
    }
}

// Manejo de acciones mediante links
if (isset($_GET['action'])) {
    $action = $_GET['action'];

    // Registrar usuario
    if ($action == 'register' && isset($_GET['user']) && isset($_GET['password'])) {
        RegisterUser($_GET['user'], $_GET['password']);
    }

    // Iniciar sesión
    if ($action == 'login' && isset($_GET['user']) && isset($_GET['password'])) {
        LoginUser($_GET['user'], $_GET['password']);
    }

    // Añadir producto al carrito
    if ($action == 'add_to_cart' && isset($_SESSION['username'])) {
        $username = $_SESSION['username'];
        if (isset($_GET['id']) && isset($_GET['name']) && isset($_GET['price']) && isset($_GET['quantity'])) {
            AddToCart($username, $_GET['id'], $_GET['name'], $_GET['quantity'], $_GET['price']);
        }
    }

    // Mostrar carrito
    if ($action == 'show_cart' && isset($_SESSION['username'])) {
        $username = $_SESSION['username'];
        $discountCode = isset($_GET['discount']) ? $_GET['discount'] : '';
        ShowCart($username, $discountCode);
    }
}
?>
