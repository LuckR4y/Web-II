<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Hotel - Dashboard</title>
<style>
  :root {
    --cor-principal: #2e7d32;
    --cor-secundaria: #f3f6fa;
    --texto-claro: #fff;
    --texto-escuro: #333;
    --borda: #ddd;
  }
  body {
    font-family: "Segoe UI", Arial, sans-serif;
    background: var(--cor-secundaria);
    margin: 0;
    padding: 0;
  }
  header {
    background: var(--cor-principal);
    color: var(--texto-claro);
    padding: 1rem 2rem;
  }
  header h1 {
    margin: 0;
    font-size: 1.4rem;
  }
  main {
    max-width: 1100px;
    margin: 1.5rem auto;
    background: #fff;
    padding: 1.5rem 2rem;
    border-radius: 10px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.08);
  }
  h2 {
    color: var(--cor-principal);
    border-left: 4px solid var(--cor-principal);
    padding-left: 8px;
  }
  form {
    background: var(--cor-secundaria);
    padding: 1rem;
    margin-bottom: 2rem;
    border-radius: 8px;
  }
  label {
    display: inline-block;
    width: 150px;
    font-weight: 600;
    margin-bottom: .3rem;
  }
  input, select {
    padding: .4rem .6rem;
    margin-bottom: .6rem;
    border: 1px solid var(--borda);
    border-radius: 5px;
  }
  input[type="submit"], button {
    background: var(--cor-principal);
    color: var(--texto-claro);
    border: none;
    padding: .5rem 1rem;
    border-radius: 5px;
    cursor: pointer;
  }
  input[type="submit"]:hover, button:hover {
    background: #005fa3;
  }
  table {
    border-collapse: collapse;
    width: 100%;
    margin-bottom: 2rem;
  }
  th {
    background: var(--cor-principal);
    color: var(--texto-claro);
    padding: 8px;
    text-align: left;
  }
  td {
    border-bottom: 1px solid var(--borda);
    padding: 8px;
  }
  tr:nth-child(even) {
    background: #f9f9f9;
  }
  .msg {
    background: #e7f5ff;
    border: 1px solid #b3e0ff;
    color: #055;
    padding: .7rem 1rem;
    border-radius: 8px;
    margin-bottom: 1rem;
  }
  .topo {
    position: relative;
    display: flex;
    justify-content: center;      
    align-items: center;
  }
  .topo a {
    position: absolute;
    right: 20px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--texto-claro);
    text-decoration: none;
    background: rgba(255,255,255,0.2);
    padding: .4rem .8rem;
    border-radius: 5px;
    font-size: .9rem;
  }
  .topo a:hover {
    background: rgba(255,255,255,0.35);
  }
</style>
</head>
<body>

<header>
  <div class="topo">
    <h1>Hotel Unaerp DashBoard</h1>
    <div>
      <a href="index.php?r=auth/logout">Sair</a>
    </div>
  </div>
</header>

<main>
  <?php if (!empty($msg)): ?>
    <div class="msg"><?= htmlspecialchars($msg) ?></div>
  <?php endif; ?>

  <h2>Cadastrar Hóspede</h2>
  <form method="post" action="index.php?r=hotel/cadastrar">
      <label>Nome:</label><input type="text" name="nome" required><br>
      <label>CPF:</label><input type="text" name="cpf" required><br>
      <label>RG:</label><input type="text" name="rg" required><br>
      <label>Telefone:</label><input type="text" name="telefone" required><br>
      <label>Data Entrada:</label><input type="date" name="dataEntrada" required><br>
      <label>Data Saída:</label><input type="date" name="dataSaida" required><br>
      <label>Quarto:</label>
      <select name="aposento" required>
          <?php while ($ap = $aposentosLivres->fetch_assoc()): ?>
            <option value="<?= $ap['id'] ?>"><?= $ap['descricao'] ?> (R$ <?= $ap['valor'] ?>)</option>
          <?php endwhile; ?>
      </select><br>
      <input type="submit" value="Cadastrar">
  </form>

  <h2>Adicionar Consumo</h2>
  <form method="post" action="index.php?r=hotel/adicionarConsumo">
      <label>Hospedagem:</label>
      <select name="hospedagem" required>
          <?php while ($h = $hospedagensAbertas->fetch_assoc()): ?>
            <option value="<?= $h['id'] ?>"><?= $h['nome'] ?> - Quarto <?= $h['descricao'] ?></option>
          <?php endwhile; ?>
      </select><br>
      <label>Consumo:</label>
      <select name="consumo" required>
          <?php foreach ($consumos as $c): ?>
            <option value="<?= htmlspecialchars($c['descricao']) ?>">
              <?= htmlspecialchars($c['descricao']) ?> (R$ <?= $c['valor'] ?>)
            </option>
          <?php endforeach; ?>
      </select><br>
      <label>Quantidade:</label> <input type="number" name="quantidade" value="1" min="1"><br>
      <input type="submit" value="Adicionar Consumo">
  </form>

  <h2>Encerrar Conta</h2>
  <form method="post" action="index.php?r=hotel/encerrar">
      <label>Código da Hospedagem:</label>
      <input type="number" name="codigoHospedagem" required>
      <input type="submit" value="Encerrar Conta">
  </form>

  <h2>Aposentos Disponíveis</h2>
  <table>
    <tr><th>Número</th><th>Descrição</th><th>Valor</th></tr>
    <?php $aps = Hotel::aposentosLivres();
  while ($ap = $aps->fetch_assoc()): ?>
      <tr>
        <td><?= $ap['numero'] ?></td>
        <td><?= $ap['descricao'] ?></td>
        <td>R$ <?= $ap['valor'] ?></td>
      </tr>
    <?php endwhile; ?>
  </table>

  <h2>Todos os Hóspedes</h2>
  <table>
    <tr>
      <th>Código Cliente</th><th>Nome</th><th>Quarto</th><th>Número Quarto</th>
      <th>Data Entrada</th><th>Data Saída</th><th>Valor Total</th><th>Status</th>
    </tr>
    <?php while ($row = $tabelaHospedes->fetch_assoc()): ?>
      <tr>
        <td><?= $row['hospede_id'] ?></td>
        <td><?= htmlspecialchars($row['nome']) ?></td>
        <td><?= htmlspecialchars($row['quarto']) ?></td>
        <td><?= $row['numero'] ?></td>
        <td><?= $row['dataEntrada'] ?></td>
        <td><?= $row['dataSaida'] ?></td>
        <td>R$ <?= number_format($row['valorTotal'], 2, ',', '.') ?></td>
        <td><?= $row['pago'] ? 'Pago' : 'Em aberto' ?></td>
      </tr>
    <?php endwhile; ?>
  </table>

  <h2>Consumos Registrados</h2>
  <table>
    <tr>
      <th>Hospedagem</th>
      <th>Hóspede</th>
      <th>Quarto</th>
      <th>Item</th>
      <th>Qtd</th>
      <th>Unitário (R$)</th>
      <th>Total (R$)</th>
    </tr>
    <?php if ($consumosLista && $consumosLista->num_rows > 0): ?>
      <?php while ($row = $consumosLista->fetch_assoc()): ?>
        <tr>
          <td>#<?= $row['hospedagem_id'] ?></td>
          <td><?= htmlspecialchars($row['hospede']) ?></td>
          <td><?= htmlspecialchars($row['quarto']) ?></td>
          <td><?= htmlspecialchars($row['item']) ?></td>
          <td><?= $row['qtd'] ?></td>
          <td><?= number_format($row['unit'], 2, ',', '.') ?></td>
          <td><?= number_format($row['total'], 2, ',', '.') ?></td>
        </tr>
      <?php endwhile; ?>
    <?php else: ?>
      <tr><td colspan="7">Nenhum consumo registrado.</td></tr>
    <?php endif; ?>
  </table>

  <h2>Limpar Histórico</h2>
  <form method="post" action="index.php?r=hotel/limparHistorico">
      <input type="submit" value="Limpar Histórico">
  </form>
</main>
</body>
</html>
