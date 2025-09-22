<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();

$erro = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['login'] ?? '');
    $senha = trim($_POST['senha'] ?? '');
    $users = require __DIR__ . '/user.php';

    if (isset($users[$login]) && $users[$login] === $senha) {
        $_SESSION['user_login'] = $login;
        header('Location: step1_pessoais.php');
        exit;
    } else {
        $erro = 'Login ou senha inválidos.';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="utf-8">
<title>Login</title>
<style>
body { font-family: Arial, sans-serif; max-width: 520px; margin: 40px auto; background:#fafafa; color:#222; }
form { border: 1px solid #ddd; padding: 16px; border-radius: 8px; background:#fff; box-shadow: 0 2px 6px rgba(0,0,0,0.05); }
label { display:block; margin: 8px 0 4px; font-weight: bold; }
input { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 6px; margin-bottom: 12px; }
button { margin-top: 8px; padding: 10px 14px; cursor:pointer; background:#0a7; color:#fff; border:none; border-radius:6px; font-weight:bold; }
button:hover { background:#096; }
.err { color: #b00020; margin-bottom: 12px; font-size: 14px; }
h1 { text-align: center; margin-bottom: 20px; }
</style>
</head>
<body>
<h1>Login</h1>
<form method="post">
  <label>Usuário</label>
  <input type="text" name="login">
  <label>Senha</label>
  <input type="password" name="senha">
  <button type="submit">Entrar</button>
</form>
</body>
</html>

