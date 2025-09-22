<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

function cart_cookie_name(): string {
    $usuario = $_SESSION['usuario'] ?? 'guest';
    return 'cart_' . md5($usuario);
}

function cart_read(): array {
    $cookieName = cart_cookie_name();
    if (!empty($_COOKIE[$cookieName])) {
        $data = json_decode($_COOKIE[$cookieName], true);
        if (is_array($data)) return $data;
    }
    return [];
}

function cart_write(array $cart): void {
    $cookieName = cart_cookie_name();
    $json = json_encode(array_values($cart), JSON_UNESCAPED_UNICODE);
    setcookie($cookieName, $json, time() + 60*60*24*30, '/');
    $_COOKIE[$cookieName] = $json;
}

function cart_add_item(int $productId, int $qty): void {
    if ($qty < 1) $qty = 1;
    $cart = cart_read();
    $found = false;
    foreach ($cart as &$item) {
        if ((int)$item['id'] === $productId) {
            $item['qty'] = (int)$item['qty'] + $qty;
            $found = true;
            break;
        }
    }
    if (!$found) { $cart[] = ['id'=>$productId, 'qty'=>$qty]; }
    cart_write($cart);
}

function cart_remove_item(int $productId): void {
    $cart = array_values(array_filter(cart_read(), fn($it) => (int)$it['id'] !== $productId));
    cart_write($cart);
}

function cart_clear(): void {
    cart_write([]);
}

function require_login(): void {
    if (empty($_SESSION['usuario'])) {
        header('Location: login.php');
        exit;
    }
}

function find_product(array $products, int $id): ?array {
    foreach ($products as $p) if ((int)$p['id'] === $id) return $p;
    return null;
}

function cart_total(array $products, array $cart): float {
    $total = 0.0;
    foreach ($cart as $item) {
        $p = find_product($products, (int)$item['id']);
        if ($p) $total += $p['preco'] * (int)$item['qty'];
    }
    return $total;
}
