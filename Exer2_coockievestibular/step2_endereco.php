<?php
require __DIR__ . '/apoio.php';
require_auth();

$login = $_SESSION['user_login'];
$data  = read_user_data($login);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data['endereco'] = [
        'cep'         => preg_replace('/\D+/', '', $_POST['cep'] ?? ''),
        'logradouro'  => trim($_POST['logradouro'] ?? ''),
        'numero'      => trim($_POST['numero'] ?? ''),
        'complemento' => trim($_POST['complemento'] ?? ''),
        'bairro'      => trim($_POST['bairro'] ?? ''),
        'cidade'      => trim($_POST['cidade'] ?? ''),
        'uf'          => strtoupper(trim($_POST['uf'] ?? '')),
    ];
    write_user_data($login, $data);
    header('Location: step3_cursos.php');
    exit;
}
$e = $data['endereco'] ?? [];
function v2($a,$k,$d=''){ return htmlspecialchars($a[$k] ?? $d, ENT_QUOTES, 'UTF-8'); }
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="utf-8">
<title>Etapa 2/3 — Endereço</title>
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
  <h2>Etapa 2/3 — Endereço</h2>
  <form method="post" autocomplete="on">
    <div class="row">
      <div>
        <label>CEP</label>
        <input class="input" name="cep" maxlength="9" value="<?=v2($e,'cep')?>">
      </div>
      <div>
        <label>Número</label>
        <input class="input" name="numero" value="<?=v2($e,'numero')?>">
      </div>
    </div>

    <label>Logradouro</label>
    <input class="input" name="logradouro" value="<?=v2($e,'logradouro')?>">

    <label>Complemento</label>
    <input class="input" name="complemento" value="<?=v2($e,'complemento')?>">

    <div class="row">
      <div>
        <label>Bairro</label>
        <input class="input" name="bairro" value="<?=v2($e,'bairro')?>">
      </div>
      <div>
        <label>Cidade</label>
        <input class="input" name="cidade" value="<?=v2($e,'cidade')?>">
      </div>
    </div>

    <label>UF</label>
    <input class="input" name="uf" maxlength="2" value="<?=v2($e,'uf')?>">

    <div class="btns">
      <a class="link" href="step1_pessoais.php">Voltar</a>
      <button type="submit">Salvar e continuar</button>
      <a class="link" href="resumo.php">Ir para Resumo</a>
    </div>
  </form>
</div>
</body>
</html>
