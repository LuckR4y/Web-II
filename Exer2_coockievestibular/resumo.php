<?php
require __DIR__ . '/apoio.php';
require_auth();

$login = $_SESSION['user_login'];
$data  = read_user_data($login);

$p = $data['pessoais'] ?? [];
$e = $data['endereco'] ?? [];
$c = $data['cursos']   ?? [];

function vv($arr, $key, $default='—') {
  return htmlspecialchars($arr[$key] ?? $default, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="utf-8">
<title>Resumo da Inscrição</title>
<style>
body { font-family: Arial, sans-serif; max-width: 900px; margin: 40px auto; background:#fafafa; color:#222; }
.container { background:#fff; border:1px solid #ddd; border-radius:8px; padding:24px; box-shadow: 0 2px 6px rgba(0,0,0,0.05); }
h2 { margin-top:0; }
.header { display:flex; justify-content:space-between; align-items:center; gap:8px; flex-wrap:wrap; }
.badge { display:inline-block; padding:6px 10px; border-radius:6px; background:#0a7; color:#fff; font-size:14px; text-decoration:none; }
.badge:hover { background:#096; }
.section { border:1px solid #ddd; background:#fff; border-radius:8px; padding:16px; margin:16px 0; }
h3 { margin:0 0 8px; }
.row { display:grid; grid-template-columns:1fr 1fr; gap:8px; }
strong { font-weight:600; }
</style>
</head>
<body>
<div class="container">
  <div class="header">
    <h2>Resumo da Inscrição</h2>
    <div>
      <a class="badge" href="step1_pessoais.php">Editar Pessoais</a>
      <a class="badge" href="step2_endereco.php">Editar Endereço</a>
      <a class="badge" href="step3_cursos.php">Editar Cursos</a>
      <a class="badge" href="logout.php">Sair</a>
    </div>
  </div>

  <div class="section">
    <h3>Dados Pessoais</h3>
    <div class="row">
      <div><strong>Nome:</strong> <?=vv($p,'nome')?></div>
      <div><strong>CPF:</strong> <?=vv($p,'cpf')?></div>
      <div><strong>Nascimento:</strong> <?=vv($p,'nascimento')?></div>
      <div><strong>Telefone:</strong> <?=vv($p,'telefone')?></div>
      <div style="grid-column:1/-1"><strong>E-mail:</strong> <?=vv($p,'email')?></div>
    </div>
  </div>

  <div class="section">
    <h3>Endereço</h3>
    <div class="row">
      <div><strong>CEP:</strong> <?=vv($e,'cep')?></div>
      <div><strong>Número:</strong> <?=vv($e,'numero')?></div>
      <div style="grid-column:1/-1"><strong>Logradouro:</strong> <?=vv($e,'logradouro')?></div>
      <div><strong>Complemento:</strong> <?=vv($e,'complemento')?></div>
      <div><strong>Bairro:</strong> <?=vv($e,'bairro')?></div>
      <div><strong>Cidade:</strong> <?=vv($e,'cidade')?></div>
      <div><strong>UF:</strong> <?=vv($e,'uf')?></div>
    </div>
  </div>

  <div class="section">
    <h3>Cursos</h3>
    <div class="row">
      <div><strong>1ª opção:</strong> <?=vv($c,'opcao1')?></div>
      <div><strong>2ª opção:</strong> <?=vv($c,'opcao2')?></div>
      <div><strong>3ª opção:</strong> <?=vv($c,'opcao3')?></div>
      <div><strong>Turno:</strong> <?=vv($c,'turno')?></div>
    </div>
  </div>
</div>
</body>
</html>
