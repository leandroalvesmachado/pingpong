<?php
require_once __DIR__ . '/Csv.php';
require_once __DIR__ . '/Game.php';

$game = new Game();

$csv1 = new Csv(__DIR__ . '/1_divisao.csv', ';');
$inscritos1 = $csv1->read();
$prize1 = $game->prize(count($inscritos1));

$csv2 = new Csv(__DIR__ . '/2_divisao.csv', ';');
$inscritos2 = $csv2->read();
$prize2 = $game->prize(count($inscritos2));

$csv3 = new Csv(__DIR__ . '/3_divisao.csv', ';');
$inscritos3 = $csv3->read();
$prize3 = $game->prize(count($inscritos3));

$csv4 = new Csv(__DIR__ . '/feminina.csv', ';');
$inscritos4 = $csv4->read();
$prize4 = $game->prize(count($inscritos4));
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Sorteio de Divisões</title>
</head>
<body>
  <h1>Sorteio de Grupos - Tênis de Mesa</h1>

  <strong>Inscritos na 1ª Divisão: <?php echo count($inscritos1); ?></strong>
  <div>TOTAL ARRECADADO: R$ <?php echo $prize1['total_arrecadado']; ?></div>
  <div>1º lugar: R$ <?php echo $prize1['1º lugar']; ?></div>
  <div>2º lugar: R$ <?php echo $prize1['2º lugar']; ?></div>
  <div>3º lugar (cada): R$ <?php echo $prize1['3º lugar (cada)']; ?></div>
  <ul>
    <?php foreach ($inscritos1 as $atleta): ?>
      <li><?= htmlspecialchars($atleta) ?></li>
    <?php endforeach; ?>
  </ul>

  <form action="script.php?arquivo=1_divisao.csv" method="post" target="_blank">
    <button type="submit">Sortear 1ª Divisão</button>
  </form>

  <hr>
  <br>

  <strong>Inscritos na 2ª Divisão: <?php echo count($inscritos2); ?></strong>
  <div>TOTAL ARRECADADO: R$ <?php echo $prize2['total_arrecadado']; ?></div>
  <div>1º lugar: R$ <?php echo $prize2['1º lugar']; ?></div>
  <div>2º lugar: R$ <?php echo $prize2['2º lugar']; ?></div>
  <div>3º lugar (cada): R$ <?php echo $prize2['3º lugar (cada)']; ?></div>
  <ul>
    <?php foreach ($inscritos2 as $atleta): ?>
      <li><?= htmlspecialchars($atleta) ?></li>
    <?php endforeach; ?>
  </ul>

  <form action="script.php?arquivo=2_divisao.csv" method="post" target="_blank">
    <button type="submit">Sortear 2ª Divisão</button>
  </form>

  <hr>
  <br>

  <strong>Inscritos na 3ª Divisão: <?php echo count($inscritos3); ?></strong>
  <div>TOTAL ARRECADADO: R$ <?php echo $prize3['total_arrecadado']; ?></div>
  <div>1º lugar: R$ <?php echo $prize3['1º lugar']; ?></div>
  <div>2º lugar: R$ <?php echo $prize3['2º lugar']; ?></div>
  <div>3º lugar (cada): R$ <?php echo $prize3['3º lugar (cada)']; ?></div>
  <ul>
    <?php foreach ($inscritos3 as $atleta): ?>
      <li><?= htmlspecialchars($atleta) ?></li>
    <?php endforeach; ?>
  </ul>

  <form action="script.php?arquivo=3_divisao.csv" method="post" target="_blank">
    <button type="submit">Sortear 3ª Divisão</button>
  </form>

  <hr>
  <br>

  <strong>Inscritos na Divisão Feminina: <?php echo count($inscritos4); ?></strong>
  <div>TOTAL ARRECADADO: R$ <?php echo $prize4['total_arrecadado']; ?></div>
  <div>1º lugar: R$ <?php echo $prize4['1º lugar']; ?></div>
  <div>2º lugar: R$ <?php echo $prize4['2º lugar']; ?></div>
  <div>3º lugar (cada): R$ <?php echo $prize4['3º lugar (cada)']; ?></div>
  <ul>
    <?php foreach ($inscritos4 as $atleta): ?>
      <li><?= htmlspecialchars($atleta) ?></li>
    <?php endforeach; ?>
  </ul>

  <form action="script.php?arquivo=feminina.csv" method="post" target="_blank">
    <button type="submit">Sortear Divisão Feminina</button>
  </form>

  <br>
</body>
</html>
