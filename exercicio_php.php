<?php 

// Exercício 1 - Vetor de colegas
$nome_colegas = array("João Henrique", "Maria Julia", "Sophia", "Julia", "Giovana");

$html = "<!DOCTYPE html>
<html lang='pt-BR'>
<head>
  <meta charset='UTF-8'>
  <title>Exercícios PHP</title>
</head>
<body>
  <h1>Exercício 1 - Vetor de Colegas</h1>";

foreach ($nome_colegas as $nome) {
    $html .= $nome . "<br>";
}

// Exercício 2 - Matriz de alunos
$alunos = array(
    array("nome" => "João",   "parcial" => 9.7, "exame" => 5.1),
    array("nome" => "Maju",   "parcial" => 5.0, "exame" => 7.0),
    array("nome" => "Soso",   "parcial" => 9.0, "exame" => 4.0),
    array("nome" => "Julia",  "parcial" => 8.0, "exame" => 7.0),
    array("nome" => "Gih",    "parcial" => 10.0,"exame" => 9.5),
);

$maior_media = 0; 
$aluno_destaque = "";

foreach ($alunos as $aluno) {
    $media = ($aluno["parcial"] + $aluno["exame"]) / 2;
    if ($media > $maior_media){
        $maior_media = $media;
        $aluno_destaque = $aluno["nome"];
    }
}

$html .= "
  <h1>Exercício 2 - Matriz de Alunos</h1>
  <table border='1' cellpadding='5' cellspacing='0'>
    <tr>
      <th>Nome</th>
      <th>Nota Parcial</th>
      <th>Nota Exame</th>
      <th>Média</th>
    </tr>";

foreach ($alunos as $aluno) {
    $media = ($aluno["parcial"] + $aluno["exame"]) / 2;
    $nomeExibicao = ($aluno["nome"] == $aluno_destaque) 
        ? "<b>{$aluno['nome']}</b>" 
        : $aluno["nome"];

    $html .= "<tr>
                <td>{$nomeExibicao}</td>
                <td>{$aluno['parcial']}</td>
                <td>{$aluno['exame']}</td>
                <td>" . number_format($media, 1, ',', '.') . "</td>
              </tr>";
}

$html .= "
  </table>";

//Exercício 3 - Matriz Original e Transposta
$matriz = [];
for ($i = 0; $i < 3; $i++){
  for ($j = 0; $j < 3; $j++) {
    $matriz[$i][$j] = rand(0, 99);
  }
}

function transpor($m){
  $t = [];
  for ($i = 0; $i < 3; $i++){
    for($j = 0; $j < 3; $j++){
      $t[$j][$i] = $m[$i][$j];
    }
  }
  return $t;
}

$transposta = transpor($matriz);

$html .= "<h1>Exercício 3 - Matriz Original</h1>
<table border='1' cellpadding='5' cellspacing='0'>";
foreach ($matriz as $linha) {
  $html .= "<tr>";
  foreach ($linha as $valor){
    $html .= "<td>$valor</td>";
  }
  $html .= "</tr>";
}
$html .= "</table>";


$html .= "<h1>Exercício 3 - Matriz Transposta</h1>
<table border='1' cellpadding='5' cellspacing='0'>";
foreach ($transposta as $linha) {
  $html .= "<tr>";
  foreach ($linha as $valor){
    $html .= "<td>$valor</td>";
  }
  $html .= "</tr>";
}
$html .= "</table>";

$html .= "
</body>
</html>";

//Exercício 4 - Formulário

$html .= " <h1> Exercício 4 - Formulário</h1>
<form method='GET' action=''>
  <label for='nome' > Digite seu nome:</label>
  <input type='text' name='nome' id='nome' required>
  <input type='submit' value='Enviar'>
</form>
";

if (isset($_GET['nome']) && !empty($_GET['nome'])){
  $nome = htmlspecialchars($_GET['nome']);
  $html .= "<h2> Olá, $nome! Seja bem-vindo. </h2>";
}

$html .= "
</body>
</html>";

echo $html;

?>
