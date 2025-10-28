<?php

require_once __DIR__ . '/../models/Hotel.php';

class HotelController
{
    private static $consumosDisponiveis = [
        ["descricao" => "Café da manhã", "valor" => 30],
        ["descricao" => "Almoço", "valor" => 50],
        ["descricao" => "Jantar", "valor" => 70],
        ["descricao" => "Serviço de Quarto", "valor" => 40],
    ];

    private static function authRequired()
    {
        if (!isset($_SESSION['usuario_id'])) {
            header("Location: index.php?r=auth/login");
            exit;
        }
    }

    public static function index()
    {
        self::authRequired();
        Hotel::seedAposentosSeVazio();

        $msg = $_GET['m'] ?? null;
        $aposentosLivres = Hotel::aposentosLivres();
        $hospedagensAbertas = Hotel::selecionarHospedagensAbertasParaSelect();
        $tabelaHospedes = Hotel::listarHospedesTabela();
        $consumosLista       = Hotel::listarConsumos();


        require __DIR__ . '/../views/hotel_dashboard.php';
    }

    public static function cadastrar()
    {
        self::authRequired();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nome = $_POST['nome'];
            $cpf = $_POST['cpf'];
            $rg = $_POST['rg'];
            $telefone = $_POST['telefone'];
            $dataEntrada = $_POST['dataEntrada'];
            $dataSaida = $_POST['dataSaida'];
            $aposento = (int)$_POST['aposento'];


            $entrada = new DateTime($dataEntrada);
            $saida = new DateTime($dataSaida);
            $dias = max(1, $entrada->diff($saida)->days);
            $valorDia = Hotel::valorDiariaPorAposento($aposento);
            $valorTotal = $dias * $valorDia;

            $hospedeId = Hotel::criarHospede($nome, $cpf, $rg, $telefone);
            $contaId = Hotel::criarConta($valorTotal);
            Hotel::setOcupado($aposento, true);
            Hotel::criarHospedagem($dataEntrada, $dataSaida, $hospedeId, $aposento, $contaId);

            $m = urlencode("Hóspede cadastrado com sucesso! Valor total da diária: R$ $valorTotal");
            header("Location: index.php?r=hotel/index&m=$m");
            exit;
        }

        header("Location: index.php?r=hotel/index");
    }

    public static function adicionarConsumo()
    {
        self::authRequired();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $hospedagem = (int)$_POST['hospedagem'];
            $descricao  = $_POST['consumo'];
            $quantidade = max(1, (int)$_POST['quantidade']);

            $valorUnit = 0;
            foreach (self::$consumosDisponiveis as $c) {
                if ($c['descricao'] === $descricao) {
                    $valorUnit = $c['valor'];
                    break;
                }
            }

            $dados = Hotel::contaEAposentoPorHospedagem($hospedagem);
            $contaId = (int)$dados['conta_id'];

            Hotel::adicionarConsumo($descricao, $quantidade, $valorUnit, $contaId);

            $m = urlencode("Consumo adicionado: $descricao x $quantidade");
            header("Location: index.php?r=hotel/index&m=$m");
            exit;
        }

        header("Location: index.php?r=hotel/index");
    }

    public static function consumos()
    {
        self::authRequired();

        $hospedagemId = isset($_GET['hospedagem']) && $_GET['hospedagem'] !== ''
            ? (int)$_GET['hospedagem']
            : null;

        $filtroHospedagens = Hotel::listaHospedagensParaFiltro();
        $consumos = Hotel::listarConsumos($hospedagemId);

        require __DIR__ . '/../views/hotel_dashboard.php';
    }


    public static function encerrar()
    {
        self::authRequired();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $hospedagemId = (int)$_POST['codigoHospedagem'];

            $dados = Hotel::contaEAposentoPorHospedagem($hospedagemId);
            $contaId = (int)$dados['conta_id'];
            $aposentoId = (int)$dados['aposento_id'];

            Hotel::pagarConta($contaId);
            Hotel::setOcupado($aposentoId, false);
            Hotel::atualizarSaidaHoje($hospedagemId);

            $m = urlencode("Conta encerrada e quarto liberado!");
            header("Location: index.php?r=hotel/index&m=$m");
            exit;
        }

        header("Location: index.php?r=hotel/index");
    }

    public static function limparHistorico()
    {
        self::authRequired();
        Hotel::limparHistorico();
        $m = urlencode("Histórico limpo!");
        header("Location: index.php?r=hotel/index&m=$m");
        exit;
    }
}
