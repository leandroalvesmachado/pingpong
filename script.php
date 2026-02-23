<?php

date_default_timezone_set('America/Fortaleza');

error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once __DIR__ . '/Csv.php';
require_once __DIR__ . '/Game.php';
require 'vendor/autoload.php';

use Dompdf\Dompdf;

$file = $_GET['arquivo'] ?? null;

try {
    switch ($file) {
        case '1_divisao.csv':
            $pdf = '1_divisao';
            $title = '1ª Divisão';
            break;
        case '2_divisao.csv':
            $pdf = '2_divisao';
            $title = '2ª Divisão';
            break;
        case '3_divisao.csv':
            $pdf = '3_divisao';
            $title = '3ª Divisão';
            break;
        case 'feminina.csv':
            $pdf = 'feminina_divisao';
            $title = 'Feminina';
            break;
        case 'iniciante.csv':
            $pdf = 'iniciante_divisao';
            $title = 'Iniciante';
            break;
        default:
            # code...
            break;
    }
    $html = "
        <!DOCTYPE html>
        <html lang='pt-BR'>
            <head>
                <meta charset='UTF-8'>
            </head>
            <body>
                <table style='width:100%; font-size: 14px; text-transform: uppercase;'>
                    <tr>
                        <th>{$title} - SORTEIO REALIZADO EM ".date('d/m/Y H:i:s')."</th>
                    </tr>
                </table>
    ";

    $game = new Game();
    $csv = new Csv(__DIR__ . "/{$file}", ';');
    $players = $csv->read();
    $total = count($players);
    $prize = $game->prize($total);

    $html .= "<div style='font-size: 12px;'>TOTAL DE ATLETAS: {$total}</div>";
    $html .= "<div style='font-size: 12px; text-transform: uppercase;'>";
    $html .= implode(', ', array_map('trim', $players));
    $html .= "</div>";
    $html .= "<br><div style='font-size: 12px; text-transform: uppercase;'>PREMIAÇÃO: 1º lugar: 40% do total / 2º lugar: 30% do total / 3º lugar (cada): 15% do total<</div><br>";

    // Ordem aleatória
    shuffle($players);

    // Criando grupos
    $groups = $game->groups($players);

    $html .= $game->group($groups);

    // Gerando partidas
    $matches = $game->matches($groups);

    // Ordem do jogos (Melhorar)
    $gamesOrder = $game->order($matches);

    $gamesOrderFinal = $game->orderFinal($gamesOrder);

    foreach ($gamesOrderFinal as $index => $match) {
        $html .= "
            <div style='page-break-inside: avoid; margin-bottom: 2px;'>
                ".$game->combat("Jogo ".($index + 1)." - {$title} - GRUPO {$match['grupo']}", $match['jogador1'], $match['jogador2'])."
            </div>
        ";
    }

    foreach ($gamesOrderFinal as $index => $match) {
        $html .= "
            <div style='page-break-inside: avoid; margin-bottom: 25px;'>
                ".$game->match("Jogo ".($index + 1)." - {$title} - GRUPO {$match['grupo']}", $match['jogador1'], $match['jogador2'])."
            </div>
        ";
    }

    if ($total == 4 || $total == 5) {
        // grupo unico
    } elseif ($total == 6) {
    } elseif ($total == 7 || $total == 8) {
        $html .= "
            <div style='page-break-inside: avoid; margin-bottom: 25px;'>
                ".$game->match("{$title} - SEMIFINAL 1 - 1º GRUPO A x 2º GRUPO B", "", "", "15px")."
                ".$game->match("{$title} - SEMIFINAL 2 - 1º GRUPO B x 2º GRUPO A", "", "", "15px")."
                ".$game->match("{$title} - FINAL - VENCEDOR SEMIFINAL 1 x VENCEDOR SEMIFINAL 2", "", "", "15px")."
            </div>
        ";
    } elseif ($total == 9) {
    } elseif ($total == 10 || $total == 11) {
        $html .= "
            <div style='page-break-inside: avoid; margin-bottom: 25px;'>
                ".$game->match("{$title} - QUARTA 1 - 2º GRUPO B x 2º GRUPO C", "", "", "15px")."
                ".$game->match("{$title} - QUARTA 2 - 2º GRUPO A x 1º GRUPO C", "", "", "15px")."
                ".$game->match("{$title} - SEMIFINAL 1 - 1º GRUPO A x VENCEDOR QUARTA 1", "", "", "15px")."
                ".$game->match("{$title} - SEMIFINAL 2 - 1º GRUPO B x VENCEDOR QUARTA 2", "", "", "15px")."
                ".$game->match("{$title} - FINAL - VENCEDOR SEMIFINAL 1 x VENCEDOR SEMIFINAL 2", "", "", "15px")."
            </div>
        ";
    } elseif ($total == 12) {
         $html .= "
            <div style='page-break-inside: avoid; margin-bottom: 25px;'>
                ".$game->match("{$title} - QUARTA 1 - 1º GRUPO A x 2º GRUPO D", "", "", "15px")."
                ".$game->match("{$title} - QUARTA 2 - 1º GRUPO B x 2º GRUPO C", "", "", "15px")."
                ".$game->match("{$title} - QUARTA 3 - 1º GRUPO C x 2º GRUPO B", "", "", "15px")."
                ".$game->match("{$title} - QUARTA 4 - 1º GRUPO D x 2º GRUPO A", "", "", "15px")."
                ".$game->match("{$title} - SEMIFINAL 1 - VENCEDOR QUARTA 1 x VENCEDOR QUARTA 2", "", "", "15px")."
                ".$game->match("{$title} - SEMIFINAL 2 - VENCEDOR QUARTA 3 x VENCEDOR QUARTA 4", "", "", "15px")."
                ".$game->match("{$title} - FINAL - VENCEDOR SEMIFINAL 1 x VENCEDOR SEMIFINAL 2", "", "", "15px")."
            </div>
        ";
    } elseif ($total == 13 || $total == 14) {
        $html .= "
            <div style='page-break-inside: avoid; margin-bottom: 25px;'>
                ".$game->match("{$title} - QUARTA 1 - 1º GRUPO A x 2º GRUPO E", "", "", "15px")."
                ".$game->match("{$title} - QUARTA 2 - 1º GRUPO B x 2º GRUPO C", "", "", "15px")."
                ".$game->match("{$title} - QUARTA 3 - 1º GRUPO C x 2º GRUPO A", "", "", "15px")."
                ".$game->match("{$title} - QUARTA 4 - 1º GRUPO D x 2º GRUPO B", "", "", "15px")."
                ".$game->match("{$title} - SEMIFINAL 1 - VENCEDOR QUARTA 1 x VENCEDOR QUARTA 2", "", "", "15px")."
                ".$game->match("{$title} - SEMIFINAL 2 - VENCEDOR QUARTA 3 x VENCEDOR QUARTA 4", "", "", "15px")."
                ".$game->match("{$title} - FINAL - VENCEDOR SEMIFINAL 1 x VENCEDOR SEMIFINAL 2", "", "", "15px")."
            </div>
        ";
    } elseif ($total == 15) {
        $html .= "
            <div style='page-break-inside: avoid; margin-bottom: 25px;'>
                ".$game->match("{$title} - OITAVA 1 - 2º GRUPO B x 2º GRUPO E", "", "", "15px")."
                ".$game->match("{$title} - OITAVA 2 - 2º GRUPO C x 2º GRUPO D", "", "", "15px")."
                ".$game->match("{$title} - QUARTA 1 - 1º GRUPO A x VENCEDOR OITAVA 1", "", "", "15px")."
                ".$game->match("{$title} - QUARTA 2 - 1º GRUPO B x VENCEDOR OITAVA 2", "", "", "15px")."
                ".$game->match("{$title} - QUARTA 3 - 1º GRUPO C x 1º GRUPO D", "", "", "15px")."
                ".$game->match("{$title} - QUARTA 4 - 1º GRUPO E x 2º GRUPO A", "", "", "15px")."
                ".$game->match("{$title} - SEMIFINAL 1 - VENCEDOR QUARTA 1 x VENCEDOR QUARTA 3", "", "", "15px")."
                ".$game->match("{$title} - SEMIFINAL 2 - VENCEDOR QUARTA 2 x VENCEDOR QUARTA 4", "", "", "15px")."
            </div>
            <div style='page-break-inside: avoid; margin-bottom: 25px;'>
                ".$game->match("{$title} - FINAL - VENCEDOR SEMIFINAL 1 x VENCEDOR SEMIFINAL 2", "", "", "15px")."
            </div>
        ";
    } elseif ($total == 16 || $total == 17) {
        $html .= "
            <div style='page-break-inside: avoid; margin-bottom: 25px;'>
                ".$game->match("{$title} - OITAVA 1 - 2º GRUPO B x 2º GRUPO E", "", "", "15px")."
                ".$game->match("{$title} - OITAVA 2 - 2º GRUPO C x 2º GRUPO D", "", "", "15px")."
                ".$game->match("{$title} - QUARTA 1 - 1º GRUPO A x VENCEDOR OITAVA 1", "", "", "15px")."
                ".$game->match("{$title} - QUARTA 2 - 1º GRUPO B x VENCEDOR OITAVA 2", "", "", "15px")."
                ".$game->match("{$title} - QUARTA 3 - 1º GRUPO C x 1º GRUPO D", "", "", "15px")."
                ".$game->match("{$title} - QUARTA 4 - 1º GRUPO E x 2º GRUPO A", "", "", "15px")."
                ".$game->match("{$title} - SEMIFINAL 1 - VENCEDOR QUARTA 1 x VENCEDOR QUARTA 3", "", "", "15px")."
                ".$game->match("{$title} - SEMIFINAL 2 - VENCEDOR QUARTA 2 x VENCEDOR QUARTA 4", "", "", "15px")."
            </div>
            <div style='page-break-inside: avoid; margin-bottom: 25px;'>
                ".$game->match("{$title} - FINAL - VENCEDOR SEMIFINAL 1 x VENCEDOR SEMIFINAL 2", "", "", "15px")."
            </div>
        ";
    } elseif ($total == 18) {
        $html .= "
            <div style='page-break-inside: avoid; margin-bottom: 25px;'>
                ".$game->match("{$title} - OITAVA 1 - 1º GRUPO E x 2º GRUPO C", "", "", "15px")."
                ".$game->match("{$title} - OITAVA 2 - 1º GRUPO F x 2º GRUPO D", "", "", "15px")."
                ".$game->match("{$title} - OITAVA 3 - 2º GRUPO A x 2º GRUPO E", "", "", "15px")."
                ".$game->match("{$title} - OITAVA 4 - 2º GRUPO B x 2º GRUPO F", "", "", "15px")."
                ".$game->match("{$title} - QUARTA 1 - 1º GRUPO 1 x VENCEDOR OITAVA 1", "", "", "15px")."
                ".$game->match("{$title} - QUARTA 2 - 1º GRUPO 2 x VENCEDOR OITAVA 2", "", "", "15px")."
                ".$game->match("{$title} - QUARTA 3 - 1º GRUPO 3 x VENCEDOR OITAVA 3", "", "", "15px")."
                ".$game->match("{$title} - QUARTA 4 - 1º GRUPO 4 x VENCEDOR OITAVA 4", "", "", "15px")."
                ".$game->match("{$title} - SEMIFINAL 1 - VENCEDOR QUARTA 1 x VENCEDOR QUARTA 3", "", "", "15px")."
                ".$game->match("{$title} - SEMIFINAL 2 - VENCEDOR QUARTA 2 x VENCEDOR QUARTA 4", "", "", "15px")."
            </div>
            <div style='page-break-inside: avoid; margin-bottom: 25px;'>
                ".$game->match("{$title} - FINAL - VENCEDOR SEMIFINAL 1 x VENCEDOR SEMIFINAL 2", "", "", "15px")."
            </div>
        ";
    }

    $html .= '
            </body>
        </html>
    ';

    $dompdf = new Dompdf();
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    $dompdf->stream("{$pdf}.pdf", ['Attachment' => false]);
} catch (Exception $e) {
    echo "Erro: " . $e->getMessage();
}
