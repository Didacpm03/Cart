<?php
function AddToCatalog($id_prod, $name, $price, $stock){
    $catalog = GetCatalog();

    foreach ($catalog->product_item as $item) {
        if ((int)$item->id_product == $id_prod) {
            return; 
        }
    }

    $item = $catalog->addChild('product_item');
    $item->addChild('id_product', $id_prod);

    $item_price = $item->addChild('info_item');
    $item_price->addChild('name', $name);
    $item_price->addChild('price', $price);
    $item_price->addChild('stock', $stock);

    $catalog->asXML('xmldb/catalog.xml');
}

/////////////////////////////////////////////////////

function GetCatalog(){
    $file = 'xmldb/catalog.xml';

    if (file_exists($file)) {
        return simplexml_load_file($file);
    } else {
        return new SimpleXMLElement('<catalog></catalog>');
    }
}

/////////////////////////////////////////////////////

function ShowCatalog() {
    $catalog = GetCatalog();

    if ($catalog->product_item->count() == 0) {
        echo "<br><strong>No se han encontrado productos.</strong><br>";
    } else {
        echo "<br><strong>Listado productos:</strong><br>";
        foreach ($catalog->product_item as $item) {
            echo "ID Producto: " . $item->id_product . "<br>";
            echo "Nombre: " . $item->info_item->name . "<br>";
            echo "Stock: " . $item->info_item->stock . "<br>";
            echo "Precio: " . $item->info_item->price . "<br>";
            echo "<br>";
        }
    }
}
?>
