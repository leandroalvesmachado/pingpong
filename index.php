<?php
require_once __DIR__ . '/Csv.php';

$csv1 = new Csv(__DIR__ . '/1_divisao.csv', ';');
$inscritos1 = $csv1->read();

$csv2 = new Csv(__DIR__ . '/2_divisao.csv', ';');
$inscritos2 = $csv2->read();

$csv3 = new Csv(__DIR__ . '/3_divisao.csv', ';');
$inscritos3 = $csv3->read();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Sorteio de Divisões</title>
</head>
<body>
  <h1>Sorteio de Grupos - Tênis de Mesa</h1>

  <strong>Inscritos na 1ª Divisão:</strong>
  <ul>
    <?php foreach ($inscritos1 as $atleta): ?>
      <li><?= htmlspecialchars($atleta) ?></li>
    <?php endforeach; ?>
  </ul>

  <form action="sorteio_divisao1.php" method="post" target="_blank">
    <button type="submit">Sortear 1ª Divisão</button>
  </form>

  <hr>
  <br>

  <strong>Inscritos na 2ª Divisão:</strong>
  <ul>
    <?php foreach ($inscritos2 as $atleta): ?>
      <li><?= htmlspecialchars($atleta) ?></li>
    <?php endforeach; ?>
  </ul>

  <form action="sorteio_divisao2.php" method="post" target="_blank">
    <button type="submit">Sortear 2ª Divisão</button>
  </form>

  <hr>
  <br>

  <strong>Inscritos na 3ª Divisão:</strong>
  <ul>
    <?php foreach ($inscritos3 as $atleta): ?>
      <li><?= htmlspecialchars($atleta) ?></li>
    <?php endforeach; ?>
  </ul>

  <form action="sorteio_divisao3.php" method="post" target="_blank">
    <button type="submit">Sortear 3ª Divisão</button>
  </form>
</body>
</html>
