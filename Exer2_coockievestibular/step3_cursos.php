<?php
require __DIR__ . '/apoio.php';
require_auth();

$login = $_SESSION['user_login'];
$data  = read_user_data($login);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data['cursos'] = [
        'opcao1' => trim($_POST['opcao1'] ?? ''),
        'opcao2' => trim($_POST['opcao2'] ?? ''),
        'opcao3' => trim($_POST['opcao3'] ?? ''),
        'turno'  => trim($_POST['turno']  ?? 'Manhã'),
    ];
    write_user_data($login, $data);
    header('Location: resumo.php');
    exit;
}
$c = $data['cursos'] ?? [];
$listaCursos = [
  'Engenharia de Software','Sistemas de Informação','Medicina',
  'Direito','Administração','Arquitetura e Urbanismo',
  'Enfermagem','Ciência de Dados','Psicologia'
];
$turnos = ['Manhã','Tarde','Noite','Integral'];

function vsel($cur,$opt){ return ($cur === $opt) ? 'selected' : ''; }
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="utf-8">
<title>Etapa 3/3 — Opções de Curso</title>
<style>
body { font-family: Arial, sans-serif; max-width: 720px; margin: 40px auto; background:#fafafa; color:#222; }
.container { background:#fff; border:1px solid #ddd; border-radius:8px; padding:24px; box-shadow: 0 2px 6px rgba(0,0,0,0.05); }
h2 { margin:0 0 16px; }
label { display:block; margin:8px 0 4px; font-weight:bold; }
.select, .input { width:100%; padding:10px; border:1px solid #ccc; border-radius:6px; margin-bottom:12px; }
.row3 { display:grid; grid-template-columns:1fr 1fr 1fr; gap:12px; }
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
  <h2>Etapa 3/3 — Opções de Curso</h2>
  <form method="post">
    <div class="row3">
      <div>
        <label>1ª opção</label>
        <select class="select" name="opcao1" required>
          <option value="">Selecione...</option>
          <?php foreach($listaCursos as $curso): ?>
            <option value="<?=$curso?>" <?=vsel($c['opcao1'] ?? '', $curso)?>><?=$curso?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div>
        <label>2ª opção</label>
        <select class="select" name="opcao2">
          <option value="">Selecione...</option>
          <?php foreach($listaCursos as $curso): ?>
            <option value="<?=$curso?>" <?=vsel($c['opcao2'] ?? '', $curso)?>><?=$curso?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div>
        <label>3ª opção</label>
        <select class="select" name="opcao3">
          <option value="">Selecione...</option>
          <?php foreach($listaCursos as $curso): ?>
            <option value="<?=$curso?>" <?=vsel($c['opcao3'] ?? '', $curso)?>><?=$curso?></option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>

    <label>Turno preferido</label>
    <select class="select" name="turno">
      <?php foreach($turnos as $t): ?>
        <option value="<?=$t?>" <?=vsel($c['turno'] ?? 'Manhã', $t)?>><?=$t?></option>
      <?php endforeach; ?>
    </select>

    <div class="btns">
      <a class="link" href="step2_endereco.php">Voltar</a>
      <button type="submit">Salvar e ver resumo</button>
      <a class="link" href="resumo.php">Ir para Resumo</a>
    </div>
  </form>
</div>
</body>
</html>
