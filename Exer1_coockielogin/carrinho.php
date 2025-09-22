<?php
require __DIR__ . '/user.php';
require __DIR__ . '/apoio.php';
require_login();

$redirect = 'produtos.php';

$action = strtolower(trim($_POST['action'] ?? ''));

switch ($action) {
    case 'add': {
        $id  = (int)($_POST['id']  ?? 0);
        $qty = (int)($_POST['qty'] ?? 1);
        if ($id > 0 && find_product($PRODUCTS, $id)) {
            if ($qty < 1) $qty = 1;
            cart_add_item($id, $qty);
        }
        break;
    }

    case 'remove': {
        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0) cart_remove_item($id);
        break;
    }

    case 'clear': {
        cart_clear();
        break;
    }

    default:
        break;
}


if (headers_sent($file, $line)) {
    die("Headers já enviados em $file:$line. Remova espaços/echo/UTF-8 BOM nos includes.");
}

header("Location: {$redirect}");
exit;
