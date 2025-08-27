<?php
// Arthur Vital Fontana
session_start();

// --- Classes ---
class Hospede {
    public $codigo, $nome, $cpf, $rg, $telefone;
    public function __construct($codigo, $nome, $cpf, $rg, $telefone) {
        $this->codigo = $codigo;
        $this->nome = $nome;
        $this->cpf = $cpf;
        $this->rg = $rg;
        $this->telefone = $telefone;
    }
}

class Aposento {
    public $codigo, $valor, $descricao, $numero, $ocupado;
    public function __construct($codigo, $valor, $descricao, $numero) {
        $this->codigo = $codigo;
        $this->valor = $valor;
        $this->descricao = $descricao;
        $this->numero = $numero;
        $this->ocupado = false;
    }
}

class Consumo {
    public $codigo, $descricao, $quantidade, $valorUnitario;
    public function __construct($codigo, $descricao, $quantidade, $valorUnitario) {
        $this->codigo = $codigo;
        $this->descricao = $descricao;
        $this->quantidade = $quantidade;
        $this->valorUnitario = $valorUnitario;
    }
}

class Conta {
    public $codigo, $valorTotal, $pago, $consumos;
    public function __construct($codigo) {
        $this->codigo = $codigo;
        $this->valorTotal = 0;
        $this->pago = false;
        $this->consumos = [];
    }
    public function addConsumo($consumo) {
        $this->consumos[] = $consumo;
        $this->valorTotal += $consumo->quantidade * $consumo->valorUnitario;
    }
}

class Hospedagem {
    public $codigo, $dataEntrada, $dataSaida, $hospede, $aposento, $conta;
    public function __construct($codigo, $dataEntrada, $dataSaida, $hospede, $aposento, $conta) {
        $this->codigo = $codigo;
        $this->dataEntrada = $dataEntrada;
        $this->dataSaida = $dataSaida;
        $this->hospede = $hospede;
        $this->aposento = $aposento;
        $this->conta = $conta;
    }

    public function calcularDiarias() {
        $entrada = new DateTime($this->dataEntrada);
        $saida = new DateTime($this->dataSaida);
        $diff = $saida->diff($entrada)->days;
        return max($diff,1);
    }

    public function calcularValorTotal() {
        return $this->calcularDiarias() * $this->aposento->valor + $this->conta->valorTotal;
    }
}

// --- Inicialização ---
if (!isset($_SESSION['aposentos'])) {
    $_SESSION['aposentos'] = [
        new Aposento(1, 200, "Solteiro", 101),
        new Aposento(2, 400, "Casal", 102),
        new Aposento(3, 600, "Suíte", 103),
    ];
}
if (!isset($_SESSION['hospedes'])) $_SESSION['hospedes'] = [];
if (!isset($_SESSION['hospedagens'])) $_SESSION['hospedagens'] = [];
if (!isset($_SESSION['contas'])) $_SESSION['contas'] = [];
if (!isset($_SESSION['consumosDisponiveis'])) $_SESSION['consumosDisponiveis'] = [
    ["descricao"=>"Café da manhã","valor"=>30],
    ["descricao"=>"Almoço","valor"=>50],
    ["descricao"=>"Jantar","valor"=>70],
    ["descricao"=>"Serviço de quarto","valor"=>40],
];

$aposentos = &$_SESSION['aposentos'];
$hospedes = &$_SESSION['hospedes'];
$hospedagens = &$_SESSION['hospedagens'];
$contas = &$_SESSION['contas'];
$consumosDisponiveis = &$_SESSION['consumosDisponiveis'];

// --- Ações ---
if (isset($_POST['cadastrar'])) {
    $codigoHospede = count($hospedes) + 1;
    $hospede = new Hospede($codigoHospede, $_POST['nome'], $_POST['cpf'], $_POST['rg'], $_POST['telefone']);
    $hospedes[] = $hospede;

    $codigoAposento = $_POST['aposento'];
    $dataEntrada = $_POST['dataEntrada'];
    $dataSaida = $_POST['dataSaida'];

    foreach ($aposentos as $ap) {
        if ($ap->codigo == $codigoAposento && !$ap->ocupado) {
            $ap->ocupado = true;
            $conta = new Conta(count($contas)+1);
            $hospedagem = new Hospedagem(count($hospedagens)+1, $dataEntrada, $dataSaida, $hospede, $ap, $conta);
            $hospedagens[] = $hospedagem;
            $contas[] = $conta;

            $valorDiarias = $hospedagem->calcularDiarias() * $ap->valor;
            echo "<p>Hóspede cadastrado no quarto {$ap->descricao}. Diárias: ".$hospedagem->calcularDiarias().", Valor das diárias: R$ {$valorDiarias}</p>";
        }
    }
}

if (isset($_POST['adicionarConsumo'])) {
    $codigoHospedagem = $_POST['hospedagem'];
    $descricao = $_POST['consumo'];
    $quantidade = intval($_POST['quantidade']);
    foreach ($hospedagens as $h) {
        if ($h->codigo == $codigoHospedagem && !$h->conta->pago) {
            foreach ($consumosDisponiveis as $c) {
                if ($c['descricao'] == $descricao) {
                    $consumo = new Consumo(count($h->conta->consumos)+1, $descricao, $quantidade, $c['valor']);
                    $h->conta->addConsumo($consumo);
                    echo "<p>Consumo adicionado: $descricao x $quantidade</p>";
                }
            }
        }
    }
}

if (isset($_POST['encerrar'])) {
    $codigoHospedagem = $_POST['codigoHospedagem'];
    foreach ($hospedagens as $h) {
        if ($h->codigo == $codigoHospedagem && !$h->conta->pago) {
            $h->conta->pago = true;
            $h->aposento->ocupado = false;
            $h->dataSaida = date("Y-m-d");
            $valorTotal = $h->calcularValorTotal();
            echo "<p>Conta encerrada! Valor total a pagar: R$ {$valorTotal}</p>";
            echo "<p>Quarto {$h->aposento->descricao} liberado.</p>";
        }
    }
}

// --- Limpar histórico ---
if (isset($_POST['limparHistorico'])) {
    foreach ($aposentos as $ap) $ap->ocupado = false; // libera todos os quartos
    $_SESSION['hospedagens'] = [];
    $_SESSION['contas'] = [];
    echo "<p>Histórico de hospedagens limpo!</p>";
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
        <?php foreach ($aposentos as $ap) if (!$ap->ocupado) echo "<option value='{$ap->codigo}'>{$ap->descricao} (R$ {$ap->valor})</option>"; ?>
    </select><br>
    <input type="submit" name="cadastrar" value="Cadastrar">
</form>

<h2>Adicionar Consumo</h2>
<form method="post">
    Hospedagem:
    <select name="hospedagem" required>
        <?php foreach ($hospedagens as $h) if (!$h->conta->pago) echo "<option value='{$h->codigo}'>{$h->hospede->nome} - Quarto {$h->aposento->descricao}</option>"; ?>
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
<?php foreach ($aposentos as $ap) if (!$ap->ocupado) echo "<li>{$ap->numero} - {$ap->descricao} (R$ {$ap->valor})</li>"; ?>
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
    foreach ($hospedagens as $hospedagem) {
        // Atualiza ocupação caso a conta esteja paga
        if ($hospedagem->conta->pago) $hospedagem->aposento->ocupado = false;
        $status = $hospedagem->conta->pago ? "Pago" : "Em aberto";
        $valorTotal = $hospedagem->calcularValorTotal();
        echo "<tr>
                <td>{$hospedagem->hospede->codigo}</td>
                <td>{$hospedagem->hospede->nome}</td>
                <td>{$hospedagem->aposento->descricao}</td>
                <td>{$hospedagem->aposento->numero}</td>
                <td>{$hospedagem->dataEntrada}</td>
                <td>{$hospedagem->dataSaida}</td>
                <td>R$ {$valorTotal}</td>
                <td>{$status}</td>
              </tr>";
    }
    ?>
    
</table>

<h2>Limpar Histórico</h2>
<form method="post">
    <input type="submit" name="limparHistorico" value="Limpar Histórico">
</form>
