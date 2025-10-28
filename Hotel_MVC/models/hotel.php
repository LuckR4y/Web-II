<?php

require_once __DIR__ . '/../db.php';

class Hotel
{
    public static function buscarUsuario($usuario)
    {
        global $mysqli;
        $st = $mysqli->prepare("SELECT id, nome, senha FROM usuarios WHERE usuario = ? LIMIT 1");
        $st->bind_param("s", $usuario);
        $st->execute();
        return $st->get_result()->fetch_assoc();
    }


    public static function seedAposentosSeVazio()
    {
        global $mysqli;
        $r = $mysqli->query("SELECT COUNT(*) AS total FROM aposentos")->fetch_assoc();
        if ((int)$r['total'] === 0) {
            $mysqli->query("INSERT INTO aposentos (numero, descricao, valor, ocupado) VALUES
                (101,'Solteiro',200,0),
                (102,'Casal',400,0),
                (103,'Suíte',600,0)");
        }
    }

    public static function aposentosLivres()
    {
        global $mysqli;
        return $mysqli->query("SELECT * FROM aposentos WHERE ocupado = 0");
    }

    public static function valorDiariaPorAposento($id)
    {
        global $mysqli;
        $st = $mysqli->prepare("SELECT valor FROM aposentos WHERE id = ?");
        $st->bind_param("i", $id);
        $st->execute();
        $r = $st->get_result()->fetch_assoc();
        return $r ? (float)$r['valor'] : 0.0;
    }

    public static function setOcupado($aposentoId, $ocupado)
    {
        global $mysqli;
        $x = $ocupado ? 1 : 0;
        $st = $mysqli->prepare("UPDATE aposentos SET ocupado = ? WHERE id = ?");
        $st->bind_param("ii", $x, $aposentoId);
        $st->execute();
    }


    public static function criarHospede($nome, $cpf, $rg, $telefone)
    {
        global $mysqli;
        $st = $mysqli->prepare("INSERT INTO hospedes (nome, cpf, rg, telefone) VALUES (?, ?, ?, ?)");
        $st->bind_param("ssss", $nome, $cpf, $rg, $telefone);
        $st->execute();
        return $mysqli->insert_id;
    }

    public static function criarConta($valorTotal)
    {
        global $mysqli;
        $st = $mysqli->prepare("INSERT INTO contas (valorTotal, pago) VALUES (?, 0)");
        $st->bind_param("d", $valorTotal);
        $st->execute();
        return $mysqli->insert_id;
    }

    public static function criarHospedagem($dataEntrada, $dataSaida, $hospedeId, $aposentoId, $contaId)
    {
        global $mysqli;
        $st = $mysqli->prepare("INSERT INTO hospedagens (dataEntrada, dataSaida, hospede_id, aposento_id, conta_id)
                                VALUES (?, ?, ?, ?, ?)");
        $st->bind_param("ssiii", $dataEntrada, $dataSaida, $hospedeId, $aposentoId, $contaId);
        $st->execute();
        return $mysqli->insert_id;
    }

    public static function selecionarHospedagensAbertasParaSelect()
    {
        global $mysqli;
        $sql = "SELECT h.id, hosp.nome, a.descricao
                FROM hospedagens h
                JOIN hospedes hosp ON hosp.id = h.hospede_id
                JOIN aposentos a ON a.id = h.aposento_id
                JOIN contas c ON c.id = h.conta_id
                WHERE c.pago = 0";
        return $mysqli->query($sql);
    }

    public static function contaEAposentoPorHospedagem($hospedagemId)
    {
        global $mysqli;
        $st = $mysqli->prepare("SELECT conta_id, aposento_id FROM hospedagens WHERE id = ?");
        $st->bind_param("i", $hospedagemId);
        $st->execute();
        return $st->get_result()->fetch_assoc();
    }

    public static function pagarConta($contaId)
    {
        global $mysqli;
        $st = $mysqli->prepare("UPDATE contas SET pago = 1 WHERE id = ?");
        $st->bind_param("i", $contaId);
        $st->execute();
    }

    public static function atualizarSaidaHoje($hospedagemId)
    {
        global $mysqli;
        $st = $mysqli->prepare("UPDATE hospedagens SET dataSaida = CURDATE() WHERE id = ?");
        $st->bind_param("i", $hospedagemId);
        $st->execute();
    }


    public static function adicionarConsumo($descricao, $quantidade, $valorUnitario, $contaId)
    {
        global $mysqli;
        $st = $mysqli->prepare("INSERT INTO consumos (descricao, quantidade, valorUnitario, conta_id) VALUES (?, ?, ?, ?)");
        $st->bind_param("sidi", $descricao, $quantidade, $valorUnitario, $contaId);
        $st->execute();


        $total = $quantidade * $valorUnitario;
        $st2 = $mysqli->prepare("UPDATE contas SET valorTotal = valorTotal + ? WHERE id = ?");
        $st2->bind_param("di", $total, $contaId);
        $st2->execute();
    }


    public static function listarHospedesTabela()
    {
        global $mysqli;
        $sql = "SELECT h.id as hospedagem_id, hosp.id as hospede_id, hosp.nome, 
                       a.descricao as quarto, a.numero, h.dataEntrada, h.dataSaida, 
                       c.valorTotal, c.pago
                FROM hospedagens h
                JOIN hospedes hosp ON hosp.id = h.hospede_id
                JOIN aposentos a ON a.id = h.aposento_id
                JOIN contas c ON c.id = h.conta_id";
        return $mysqli->query($sql);
    }


    public static function limparHistorico()
    {
        global $mysqli;
        $mysqli->query("UPDATE aposentos SET ocupado = 0");
        $mysqli->query("DELETE FROM consumos");
        $mysqli->query("DELETE FROM hospedagens");
        $mysqli->query("DELETE FROM contas");
        $mysqli->query("DELETE FROM hospedes");
    }

    public static function listarConsumos($hospedagemId = null)
    {
        global $mysqli;

        $sql = "SELECT 
                    h.id AS hospedagem_id,
                    hosp.nome AS hospede,
                    a.descricao AS quarto,
                    cns.descricao AS item,
                    cns.quantidade AS qtd,
                    cns.valorUnitario AS unit,
                    (cns.quantidade * cns.valorUnitario) AS total
                FROM consumos cns
                JOIN contas ct ON ct.id = cns.conta_id
                JOIN hospedagens h ON h.conta_id = ct.id
                JOIN hospedes hosp ON hosp.id = h.hospede_id
                JOIN aposentos a ON a.id = h.aposento_id";

        if ($hospedagemId) {
            $sql .= " WHERE h.id = ?";
            $st = $mysqli->prepare($sql);
            $st->bind_param("i", $hospedagemId);
            $st->execute();
            return $st->get_result();
        }

        $sql .= " ORDER BY hosp.nome ASC, h.id ASC";
        return $mysqli->query($sql);
    }

    public static function listaHospedagensParaFiltro()
    {
        global $mysqli;
        $sql = "SELECT h.id, CONCAT(hosp.nome, ' - Quarto ', a.descricao) AS label
                FROM hospedagens h
                JOIN hospedes hosp ON hosp.id = h.hospede_id
                JOIN aposentos a   ON a.id = h.aposento_id
                ORDER BY hosp.nome ASC";
        return $mysqli->query($sql);
    }
}
