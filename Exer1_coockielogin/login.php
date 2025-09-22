<?php
require __DIR__ . '/user.php';
require __DIR__ . '/apoio.php';

$erro = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = trim($_POST['usuario'] ?? '');
    $senha   = trim($_POST['senha'] ?? '');

    if (isset($USERS[$usuario]) && $USERS[$usuario] === $senha) {
        $_SESSION['usuario'] = $usuario;
        header('Location: produtos.php');
        exit;
    } else {
        $erro = 'Credenciais inválidas.';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="utf-8">
<title>Login</title>
<style>
body { font-family: Arial, sans-serif; max-width: 520px; margin: 40px auto; }
form { border: 1px solid #ddd; padding: 16px; border-radius: 8px; }
label { display:block; margin: 8px 0 4px; }
input { width: 100%; padding: 8px; }
button { margin-top: 12px; padding: 10px 14px; cursor:pointer; }
.err { color: #b00020; margin-bottom: 12px; }
.info { background:#f6f6f6; padding:10px; border-radius:6px; margin-bottom:12px; }
</style>
</head>
<body>
<h1>Login</h1>

<div class="info">
  <strong>Usuários de teste</strong><br>
  vitas / teste<br>
  prof / aulaweb
</div>

<?php if ($erro): ?><div class="err"><?= htmlspecialchars($erro) ?></div><?php endif; ?>

<form method="post" action="">
  <label for="usuario">Usuário</label>
  <input type="text" id="usuario" name="usuario" required>

  <label for="senha">Senha</label>
  <input type="password" id="senha" name="senha" required>

  <button type="submit">Entrar</button>
</form>
</body>
</html>
