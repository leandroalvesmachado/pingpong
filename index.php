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
  <title>AABB Fortaleza | Torneio Interno</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
</head>
<body>
  <div class="container">
    <div class="row text-center">
      <div class="col">
        <h1>AABB Fortaleza 2025</h1>
      </div>
    </div>
    <div class="row text-center">
      <div class="col">
        <h1>4º Torneio Interno de Tênis de Mesa</h1>
      </div>
    </div>
  </div>
  <div class="container mb-5">
    <div class="row">
      <div class="col">
        <div class="table-responsive">
          <table class="table table-striped table-hover table-bordered">
            <thead>
              <tr class="text-center">
                <th colspan="5" class="table-success">1ª Divisão (ABS. Masculino A, B e C): <?php echo count($inscritos1); ?></th>
              </tr>
            </thead>
            <tbody>
              <tr class="text-center">
                <td class="table-primary"><strong>Total: R$ <?php echo $prize1['total_arrecadado']; ?></strong></td>
                <td class="table-primary"><strong>1º lugar: R$ <?php echo $prize1['1º lugar']; ?></strong></td>
                <td class="table-primary"><strong>2º lugar: R$ <?php echo $prize1['2º lugar']; ?></strong></td>
                <td class="table-primary"><strong>3º lugar (cada): R$ <?php echo $prize1['3º lugar (cada)']; ?></strong></td>
              </tr>
              <?php foreach ($inscritos1 as $atleta): ?>
              <tr class="text-center">
                <td colspan="5"><?= htmlspecialchars($atleta) ?></td>
              </tr>
              <?php endforeach; ?>
              <tr>
                <td colspan="5">
                  <form action="script.php?arquivo=1_divisao.csv" method="post" target="_blank">
                    <div class="d-grid gap-2 col-6 mx-auto">
                      <button type="submit" class="btn btn-primary">Sorteio 1ª Divisão</button>
                    </div>
                  </form>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <div class="container mb-5">
    <div class="row">
      <div class="col">
        <div class="table-responsive">
          <table class="table table-striped table-hover table-bordered">
            <thead>
              <tr class="text-center">
                <th colspan="5" class="table-success">2ª Divisão (ABS. Masculino D, E e ABS. Feminino B e C): <?php echo count($inscritos2); ?></th>
              </tr>
            </thead>
            <tbody>
              <tr class="text-center">
                <td class="table-primary"><strong>Total: R$ <?php echo $prize2['total_arrecadado']; ?></strong></td>
                <td class="table-primary"><strong>1º lugar: R$ <?php echo $prize2['1º lugar']; ?></strong></td>
                <td class="table-primary"><strong>2º lugar: R$ <?php echo $prize2['2º lugar']; ?></strong></td>
                <td class="table-primary"><strong>3º lugar (cada): R$ <?php echo $prize2['3º lugar (cada)']; ?></strong></td>
              </tr>
              <?php foreach ($inscritos2 as $atleta): ?>
              <tr class="text-center">
                <td colspan="5"><?= htmlspecialchars($atleta) ?></td>
              </tr>
              <?php endforeach; ?>
              <tr>
                <td colspan="5">
                  <form action="script.php?arquivo=2_divisao.csv" method="post" target="_blank">
                    <div class="d-grid gap-2 col-6 mx-auto">
                      <button type="submit" class="btn btn-primary">Sorteio 2ª Divisão</button>
                    </div>
                  </form>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <div class="container mb-5">
    <div class="row">
      <div class="col">
        <div class="table-responsive">
          <table class="table table-striped table-hover table-bordered">
            <thead>
              <tr class="text-center">
                <th colspan="5" class="table-success">3ª Divisão (ABS. Masculino F e ABS. Feminino D): <?php echo count($inscritos3); ?></th>
              </tr>
            </thead>
            <tbody>
              <tr class="text-center">
                <td class="table-primary"><strong>Total: R$ <?php echo $prize3['total_arrecadado']; ?></strong></td>
                <td class="table-primary"><strong>1º lugar: R$ <?php echo $prize3['1º lugar']; ?></strong></td>
                <td class="table-primary"><strong>2º lugar: R$ <?php echo $prize3['2º lugar']; ?></strong></td>
                <td class="table-primary"><strong>3º lugar (cada): R$ <?php echo $prize3['3º lugar (cada)']; ?></strong></td>
              </tr>
              <?php foreach ($inscritos3 as $atleta): ?>
              <tr class="text-center">
                <td colspan="5"><?= htmlspecialchars($atleta) ?></td>
              </tr>
              <?php endforeach; ?>
              <tr>
                <td colspan="5">
                  <form action="script.php?arquivo=3_divisao.csv" method="post" target="_blank">
                    <div class="d-grid gap-2 col-6 mx-auto">
                      <button type="submit" class="btn btn-primary">Sorteio 3ª Divisão</button>
                    </div>
                  </form>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <div class="container mb-5">
    <div class="row">
      <div class="col">
        <div class="table-responsive">
          <table class="table table-striped table-hover table-bordered">
            <thead>
              <tr class="text-center">
                <th colspan="5" class="table-success">Inscritos na Divisão Feminina: <?php echo count($inscritos4); ?></th>
              </tr>
            </thead>
            <tbody>
              <tr class="text-center">
                <td class="table-primary"><strong>Total: R$ <?php echo $prize4['total_arrecadado']; ?></strong></td>
                <td class="table-primary"><strong>1º lugar: R$ <?php echo $prize4['1º lugar']; ?></strong></td>
                <td class="table-primary"><strong>2º lugar: R$ <?php echo $prize4['2º lugar']; ?></strong></td>
                <td class="table-primary"><strong>3º lugar (cada): R$ <?php echo $prize4['3º lugar (cada)']; ?></strong></td>
              </tr>
              <?php foreach ($inscritos4 as $atleta): ?>
              <tr class="text-center">
                <td colspan="5"><?= htmlspecialchars($atleta) ?></td>
              </tr>
              <?php endforeach; ?>
              <tr>
                <td colspan="5">
                  <form action="script.php?arquivo=feminina.csv" method="post" target="_blank">
                    <div class="d-grid gap-2 col-6 mx-auto">
                      <button type="submit" class="btn btn-primary">Sorteio Divisão Feminina</button>
                    </div>
                  </form>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
</body>
</html>
