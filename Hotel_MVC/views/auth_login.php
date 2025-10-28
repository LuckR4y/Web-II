<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login - Sistema de Hotel</title>
<style>
  :root {
    --verde: #2e7d32;
    --verde-hover: #256428;
    --fundo: #f4f8f5;
  }
  body {
    font-family: "Segoe UI", Arial, sans-serif;
    background: var(--fundo);
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100vh;
    margin: 0;
  }
  .login-box {
    background: #fff;
    padding: 2rem 2.5rem;
    border-radius: 10px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    width: 350px;
  }
  h2 {
    color: var(--verde);
    text-align: center;
    margin-bottom: 1.5rem;
  }
  input[type="text"], input[type="password"] {
    width: 100%;
    padding: .6rem;
    border: 1px solid #ccc;
    border-radius: 6px;
    margin-bottom: 1rem;
  }
  input[type="submit"] {
    width: 100%;
    background: var(--verde);
    color: #fff;
    border: none;
    padding: .6rem;
    border-radius: 6px;
    font-size: 1rem;
    cursor: pointer;
  }
  input[type="submit"]:hover {
    background: var(--verde-hover);
  }
  .erro {
    background: #ffeaea;
    color: #a00;
    border: 1px solid #f5b5b5;
    padding: .6rem;
    border-radius: 6px;
    margin-bottom: 1rem;
    text-align: center;
  }
</style>
</head>
<body>
  <div class="login-box">
    <h2>Login do Sistema</h2>
    <?php if (!empty($error)): ?>
      <div class="erro"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post" action="index.php?r=auth/login">
      <input type="text" name="usuario" placeholder="Usuário" required>
      <input type="password" name="senha" placeholder="Senha" required>
      <input type="submit" name="login" value="Entrar">
    </form>
  </div>
</body>
</html>
