<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();

function require_auth(): void {
    if (empty($_SESSION['user_login'])) {
        header('Location: login.php');
        exit;
    }
}

function path_user_json(string $login): string {
    $login = preg_replace('/[^a-z0-9_.-]/i', '_', $login);
    return __DIR__ . "/data/{$login}.json";
}

function read_user_data(string $login): array {
    $file = path_user_json($login);
    if (!is_file($file)) return []; 
    $json = file_get_contents($file);
    $data = json_decode($json, true);
    return is_array($data) ? $data : [];
}

function write_user_data(string $login, array $data): bool {
    $file = path_user_json($login);
    if (!is_dir(dirname($file))) mkdir(dirname($file), 0777, true);
    $json = json_encode($data, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
    return (bool) file_put_contents($file, $json);
}


function v(array $a, string $k, $default='') { return $a[$k] ?? $default; }
