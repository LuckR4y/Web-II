<?php
require __DIR__ . '/apoio.php';
require_auth();

$login = $_SESSION['user_login'];
$data  = read_user_data($login);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data['pessoais'] = [
        'nome'        => trim($_POST['nome'] ?? ''),
        'cpf'         => preg_replace('/\D+/', '', $_POST['cpf'] ?? ''),
        'nascimento'  => trim($_POST['nascimento'] ?? ''),
        'telefone'    => trim($_POST['telefone'] ?? ''),
        'email'       => trim($_POST['email'] ?? ''),
    ];
    write_user_data($login, $data);
    header('Location: step2_endereco.php');
    exit;
}
$p = $data['pessoais'] ?? [];
function v2($a,$k,$d=''){ return htmlspecialchars($a[$k] ?? $d, ENT_QUOTES, 'UTF-8'); }
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="utf-8">
<title>Etapa 1/3 — Dados Pessoais</title>
<style>
body { font-family: Arial, sans-serif; max-width: 720px; margin: 40px auto; background:#fafafa; color:#222; }
.container { background:#fff; border:1px solid #ddd; border-radius:8px; padding:24px; box-shadow: 0 2px 6px rgba(0,0,0,0.05); }
h2 { margin:0 0 16px; }
label { display:block; margin:8px 0 4px; font-weight:bold; }
.input { width:100%; padding:10px; border:1px solid #ccc; border-radius:6px; margin-bottom:12px; }
.row { display:grid; grid-template-columns:1fr 1fr; gap:12px; }
.btns { display:flex; gap:8px; margin-top:12px; flex-wrap:wrap; }
a,button { padding:10px 14px; border-radius:6px; text-decoration:none; font-weight:bold; cursor:pointer; }
button { background:#0a7; color:#fff; border:none; }
button:hover { background:#096; }
.link { background:#eaf8f2; color:#055; border:1px solid #bfe8d7; }
.link:hover { background:#d8f1e8; }
</style>
</head>
<body>
<div class="container">
  <h2>Etapa 1/3 — Dados Pessoais</h2>
  <form method="post" autocomplete="on">
    <label>Nome completo</label>
    <input class="input" name="nome" required value="<?=v2($p,'nome')?>">

    <div class="row">
      <div>
        <label>CPF</label>
        <input class="input" name="cpf" required maxlength="14" value="<?=v2($p,'cpf')?>">
      </div>
      <div>
        <label>Data de nascimento</label>
        <input class="input" type="date" name="nascimento" required value="<?=v2($p,'nascimento')?>">
      </div>
    </div>

    <div class="row">
      <div>
        <label>Telefone</label>
        <input class="input" name="telefone" value="<?=v2($p,'telefone')?>">
      </div>
      <div>
        <label>E-mail</label>
        <input class="input" type="email" name="email" value="<?=v2($p,'email')?>">
      </div>
    </div>

    <div class="btns">
      <a class="link" href="logout.php">Sair</a>
      <button type="submit">Salvar e continuar</button>
      <a class="link" href="resumo.php">Ir para Resumo</a>
    </div>
  </form>
</div>
</body>
</html>
