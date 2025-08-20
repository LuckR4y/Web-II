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
  </table>
</body>
</html>";

echo $html;
?>
