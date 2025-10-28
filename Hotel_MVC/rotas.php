<?php

require_once __DIR__ . '/controllers/AuthController.php';
require_once __DIR__ . '/controllers/HotelController.php';

$route = $_GET['r'] ?? 'auth/login';
list($ctrl, $acao) = array_pad(explode('/', $route, 2), 2, null);

switch ($ctrl) {
    case 'auth':
        if ($acao === 'login') {
            AuthController::login();
        } elseif ($acao === 'logout') {
            AuthController::logout();
        } else {
            http_response_code(404);
            echo 'Ação inválida';
        }
        break;

    case 'hotel':
        if ($acao === 'index') {
            HotelController::index();
        } elseif ($acao === 'cadastrar') {
            HotelController::cadastrar();
        } elseif ($acao === 'adicionarConsumo') {
            HotelController::adicionarConsumo();
        } elseif ($acao === 'consumos') {
            HotelController::consumos();
        } elseif ($acao === 'encerrar') {
            HotelController::encerrar();
        } elseif ($acao === 'limparHistorico') {
            HotelController::limparHistorico();
        } else {
            http_response_code(404);
            echo 'Ação inválida';
        }
        break;

    default:
        http_response_code(404);
        echo 'Rota inválida';
}
