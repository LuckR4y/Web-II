<?php 
// Arthur Vital Fontana
// 839832

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hotel";

// Criando a conexão
$mysqli = new mysqli($servername, $username, $password, $dbname);
if ($mysqli->connect_error) die("Falha na conexão: " . $mysqli->connect_error);

// Criar aposentos automaticamente se não houver nenhum
$res = $mysqli->query("SELECT COUNT(*) as total FROM aposentos");
$row = $res->fetch_assoc();
if ($row['total'] == 0) {
    $mysqli->query("INSERT INTO aposentos (numero, descricao, valor) VALUES (101, 'Solteiro', 200)");
    $mysqli->query("INSERT INTO aposentos (numero, descricao, valor) VALUES (102, 'Casal', 400)");
    $mysqli->query("INSERT INTO aposentos (numero, descricao, valor) VALUES (103, 'Suíte', 600)");
}

// Consumos disponíveis
$consumosDisponiveis = [
    ["descricao" => "Café da manhã", "valor" => 30],
    ["descricao" => "Almoço", "valor" => 50],
    ["descricao" => "Jantar", "valor" => 70],
    ["descricao" => "Serviço de Quarto", "valor" => 40],
];

// --- Cadastrar Hóspede ---
if (isset($_POST['cadastrar'])){
    $stmt = $mysqli->prepare("INSERT INTO hospedes (nome, cpf, rg, telefone) VALUES(?, ?, ?, ?)");
    $stmt->bind_param("ssss", $_POST['nome'], $_POST['cpf'], $_POST['rg'], $_POST['telefone']);
    $stmt->execute();
    $hospede_id = $stmt->insert_id;

    $mysqli->query("INSERT INTO contas (valorTotal, pago) VALUES (0, 0)");
    $conta_id = $mysqli->insert_id;

    $codigoAposento = $_POST['aposento'];
    $mysqli->query("UPDATE aposentos SET ocupado = 1 WHERE id = $codigoAposento");

    $stmt = $mysqli->prepare("INSERT INTO hospedagens (dataEntrada, dataSaida, hospede_id, aposento_id, conta_id) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssiii", $_POST['dataEntrada'], $_POST['dataSaida'], $hospede_id, $codigoAposento, $conta_id);
    $stmt->execute();

    echo "<p>Hóspede cadastrado com sucesso!</p>";
}

// --- Adicionar Consumo ---
if (isset($_POST['adicionarConsumo'])){
    $codigoHospedagem = $_POST['hospedagem'];
    $descricao = $_POST['consumo'];
    $quantidade = intval($_POST['quantidade']);
    $valorUnitario = 0;

    foreach ($consumosDisponiveis as $c) {
        if ($c['descricao'] == $descricao) $valorUnitario = $c['valor'];
    }

    $res = $mysqli->query("SELECT conta_id FROM hospedagens WHERE id = $codigoHospedagem");
    $conta = $res->fetch_assoc();
    $conta_id = $conta['conta_id'];

    $stmt = $mysqli->prepare("INSERT INTO consumos (descricao, quantidade, valorUnitario, conta_id) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sidi", $descricao, $quantidade, $valorUnitario, $conta_id);
    $stmt->execute();

    $totalConsumo = $quantidade * $valorUnitario;
    $mysqli->query("UPDATE contas SET valorTotal = valorTotal + $totalConsumo WHERE id = $conta_id");

    echo "<p>Consumo adicionado: $descricao x $quantidade</p>";
}

// --- Encerrar Conta ---
if (isset($_POST['encerrar'])) {
    $codigoHospedagem = $_POST['codigoHospedagem'];

    $res = $mysqli->query("SELECT conta_id, aposento_id FROM hospedagens WHERE id = $codigoHospedagem");
    $dados = $res->fetch_assoc();
    $conta_id = $dados['conta_id'];
    $aposento_id = $dados['aposento_id'];

    $mysqli->query("UPDATE contas SET pago = 1 WHERE id = $conta_id");
    $mysqli->query("UPDATE aposentos SET ocupado = 0 WHERE id = $aposento_id");

    $hoje = date("Y-m-d");
    $mysqli->query("UPDATE hospedagens SET dataSaida = '$hoje' WHERE id = $codigoHospedagem");

    echo "<p>Conta encerrada e quarto liberado!</p>";
}

// --- Limpar Histórico ---
if (isset($_POST['limparHistorico'])) {
    $mysqli->query("UPDATE aposentos SET ocupado = 0");
    $mysqli->query("DELETE FROM consumos");
    $mysqli->query("DELETE FROM hospedagens");
    $mysqli->query("DELETE FROM contas");
    $mysqli->query("DELETE FROM hospedes");
    echo "<p>Histórico limpo!</p>";
}
?>

<h2>Cadastrar Hóspede</h2>
<form method="post">
    Nome: <input type="text" name="nome" required><br>
    CPF: <input type="text" name="cpf" required><br>
    RG: <input type="text" name="rg" required><br>
    Telefone: <input type="text" name="telefone" required><br>
    Data Entrada: <input type="date" name="dataEntrada" required><br>
    Data Saída: <input type="date" name="dataSaida" required><br>
    Quarto:
    <select name="aposento" required>
        <?php
        $res = $mysqli->query("SELECT * FROM aposentos WHERE ocupado = 0");
        while ($ap = $res->fetch_assoc()) {
            echo "<option value='{$ap['id']}'>{$ap['descricao']} (R$ {$ap['valor']})</option>";
        }
        ?>
    </select><br>
    <input type="submit" name="cadastrar" value="Cadastrar">
</form>

<h2>Adicionar Consumo</h2>
<form method="post">
    Hospedagem:
    <select name="hospedagem" required>
        <?php
        $res = $mysqli->query("SELECT h.id, hosp.nome, a.descricao FROM hospedagens h
            JOIN hospedes hosp ON hosp.id = h.hospede_id
            JOIN aposentos a ON a.id = h.aposento_id
            JOIN contas c ON c.id = h.conta_id
            WHERE c.pago = 0");
        while ($h = $res->fetch_assoc()) {
            echo "<option value='{$h['id']}'>{$h['nome']} - Quarto {$h['descricao']}</option>";
        }
        ?>
    </select><br>
    Consumo:
    <select name="consumo" required>
        <?php foreach ($consumosDisponiveis as $c) echo "<option value='{$c['descricao']}'>{$c['descricao']} (R$ {$c['valor']})</option>"; ?>
    </select><br>
    Quantidade: <input type="number" name="quantidade" value="1" min="1"><br>
    <input type="submit" name="adicionarConsumo" value="Adicionar Consumo">
</form>

<h2>Encerrar Conta</h2>
<form method="post">
    Código da Hospedagem: <input type="number" name="codigoHospedagem" required><br>
    <input type="submit" name="encerrar" value="Encerrar Conta">
</form>

<h2>Aposentos Disponíveis</h2>
<ul>
<?php
$res = $mysqli->query("SELECT * FROM aposentos WHERE ocupado = 0");
while ($ap = $res->fetch_assoc()) {
    echo "<li>{$ap['numero']} - {$ap['descricao']} (R$ {$ap['valor']})</li>";
}
?>
</ul>

<h2>Todos os Hóspedes</h2>
<table border="1" cellpadding="5">
<tr>
    <th>Código Cliente</th>
    <th>Nome</th>
    <th>Quarto</th>
    <th>Número Quarto</th>
    <th>Data Entrada</th>
    <th>Data Saída</th>
    <th>Valor Total</th>
    <th>Status</th>
</tr>
<?php
$sql = "SELECT h.id as hospedagem_id, hosp.id as hospede_id, hosp.nome, 
               a.descricao as quarto, a.numero, h.dataEntrada, h.dataSaida, 
               c.valorTotal, c.pago
        FROM hospedagens h
        JOIN hospedes hosp ON hosp.id = h.hospede_id
        JOIN aposentos a ON a.id = h.aposento_id
        JOIN contas c ON c.id = h.conta_id";
$res = $mysqli->query($sql);
while ($row = $res->fetch_assoc()) {
    $status = $row['pago'] ? "Pago" : "Em aberto";
    echo "<tr>
        <td>{$row['hospede_id']}</td>
        <td>{$row['nome']}</td>
        <td>{$row['quarto']}</td>
        <td>{$row['numero']}</td>
        <td>{$row['dataEntrada']}</td>
        <td>{$row['dataSaida']}</td>
        <td>R$ {$row['valorTotal']}</td>
        <td>{$status}</td>
    </tr>";
}
?>
</table>

<h2>Limpar Histórico</h2>
<form method="post">
    <input type="submit" name="limparHistorico" value="Limpar Histórico">
</form>
