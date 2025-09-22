<?php
require __DIR__ . '/user.php';
require __DIR__ . '/apoio.php';
require_login();

$carrinho = cart_read();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="utf-8">
<title>Produtos</title>
<style>
body { font-family: Arial, sans-serif; max-width: 1000px; margin: 24px auto; }
header { display:flex; justify-content:space-between; align-items:center; margin-bottom: 16px; }
.grid { display:grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap:16px; }
.card { border:1px solid #ddd; border-radius:8px; padding:12px; }
.card h3 { margin: 0 0 8px; }
.price { color:#0a7; font-weight:bold; }
.carrinho { margin-top:24px; }
table { width:100%; border-collapse: collapse; }
th, td { border-bottom:1px solid #eee; padding:8px; text-align:left; }
.actions a, .actions form button { margin-right:8px; }
</style>
</head>
<body>
<header>
  <div>
    <h1>Loja – Produtos</h1>
    <div>Logado como: <strong><?= htmlspecialchars($_SESSION['usuario'] ?? '') ?></strong></div>
  </div>
  <div><a href="logout.php">Sair</a></div>
</header>

<section class="grid">
<?php foreach ($PRODUCTS as $p): ?>
  <div class="card">
    <h3><?= htmlspecialchars($p['nome']) ?></h3>
    <div class="price">R$ <?= number_format($p['preco'], 2, ',', '.') ?></div>
    <form method="post" action="carrinho.php" style="margin-top: 8px;">
      <input type="hidden" name="action" value="add">
      <input type="hidden" name="id" value="<?= (int)$p['id'] ?>">
      <label>
        Qtd:
        <input type="number" name="qty" value="1" min="1" style="width:80px;">
      </label>
      <button type="submit">Adicionar ao carrinho</button>
    </form>
  </div>
<?php endforeach; ?>
</section>

<section class="carrinho">
  <h2>Seu Carrinho (cookie)</h2>
  <div class="actions" style="margin-bottom:8px;">
    <form method="post" action="carrinho.php" style="display:inline;">
      <input type="hidden" name="action" value="clear">
      <button type="submit">Esvaziar carrinho</button>
    </form>
  </div>

  <?php if (empty($carrinho)): ?>
    <p>Seu carrinho está vazio.</p>
  <?php else: ?>
    <table>
      <thead>
        <tr>
          <th>Produto</th>
          <th>Qtd</th>
          <th>Preço</th>
          <th>Subtotal</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php
        $total = 0;
        foreach ($carrinho as $item):
          $prod = find_product($PRODUCTS, (int)$item['id']);
          if (!$prod) continue;
          $sub = $prod['preco'] * (int)$item['qty'];
          $total += $sub;
        ?>
        <tr>
          <td><?= htmlspecialchars($prod['nome']) ?></td>
          <td><?= (int)$item['qty'] ?></td>
          <td>R$ <?= number_format($prod['preco'], 2, ',', '.') ?></td>
          <td>R$ <?= number_format($sub, 2, ',', '.') ?></td>
          <td>
            <form method="post" action="carrinho.php">
              <input type="hidden" name="action" value="remove">
              <input type="hidden" name="id" value="<?= (int)$prod['id'] ?>">
              <button type="submit">Remover</button>
            </form>
          </td>
        </tr>
        <?php endforeach; ?>
        <tr>
          <th colspan="3" style="text-align:right;">Total</th>
          <th colspan="2">R$ <?= number_format($total, 2, ',', '.') ?></th>
        </tr>
      </tbody>
    </table>
  <?php endif; ?>
</section>

</body>
</html>
