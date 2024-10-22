<?php
function RegisterUser($username, $password) {
    echo "Registrado correctamente <br>";
    echo "Bienvenido " . $username . "<br>";
    echo "<br>";
    $user = GetUser();

    $item = $user->addChild('user_item');

    $item->addChild('username', $username);
    $item->addChild('password', $password);

    $user_info = $item->addChild('info_user');
    $user_info->addChild('name', 'didac');
    $user_info->addChild('last_name', 'paz');
    
    $user->asXML('xmldb/users.xml');
}

function GetUser() {
    $file = 'xmldb/users.xml';

    if (file_exists($file)) {
        return simplexml_load_file($file);
    } else {
        return new SimpleXMLElement('<user></user>');
    }
}
?>
