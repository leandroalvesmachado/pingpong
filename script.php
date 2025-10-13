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

    $html .= "<br><div style='font-size: 13px;'>TOTAL DE ATLETAS: {$total}</div>";
    
    $html .= "<div style='font-size: 14px;'>";
    $html .= implode(', ', array_map('trim', $players));
    $html .= "</div>";
    $html .= "<br><div style='font-size: 14px;'>PREMIAÇÃO</div>";
    $html .= "<div style='font-size: 14px;'>1º lugar: 40% do total / 2º lugar: 30% do total / 3º lugar (cada): 15% para cada um</div><br>";


    // Ordem aleatória
    shuffle($players);

    // Criando grupos
    $groups = $game->groups($players);

    $html .= $game->group($groups);

    // Gerando partidas
    $matches = $game->matches($groups);

    // Ordem do jogos (Melhorar)
    $gamesOrder = $game->order($matches);

    foreach ($gamesOrder as $match) {
        $html .= "
            <div style='page-break-inside: avoid; margin-bottom: 25px;'>
                ".$game->match("{$title} - GRUPO {$match['grupo']} - JOGO {$match['jogo']}", $match['jogador1'], $match['jogador2'])."
            </div>
        ";
    }

    if ($total == 4 || $total == 5) {
        // grupo unico
    } elseif ($total == 6) {
    } elseif ($total == 7) {
        $html .= "
            <div style='page-break-inside: avoid; margin-bottom: 25px;'>
                ".$game->match("{$title} - SEMIFINAL 1 - 1º GRUPO 1 x 2º GRUPO 2", "", "", "15px")."
                ".$game->match("{$title} - SEMIFINAL 2 - 1º GRUPO 2 x 2º GRUPO 1", "", "", "15px")."
                ".$game->match("{$title} - FINAL - VENCEDOR S1 x VENCEDOR S2", "", "", "15px")."
            </div>
        ";
    } elseif ($total == 8) {
        $html .= "
            <div style='page-break-inside: avoid; margin-bottom: 25px;'>
                ".$game->match("{$title} - SEMIFINAL 1 - 1º GRUPO 1 x 2º GRUPO 2", "", "", "15px")."
                ".$game->match("{$title} - SEMIFINAL 2 - 1º GRUPO 2 x 2º GRUPO 1", "", "", "15px")."
                ".$game->match("{$title} - FINAL - VENCEDOR S1 x VENCEDOR S2", "", "", "15px")."
            </div>
        ";
    } elseif ($total == 10) {
        $html .= "
            <div style='page-break-inside: avoid; margin-bottom: 25px;'>
                ".$game->match("{$title} - QUARTA 1 - 2º GRUPO 2 x 2º GRUPO 3", "", "", "15px")."
                ".$game->match("{$title} - QUARTA 2 - 2º GRUPO 1 x 1º GRUPO 3", "", "", "15px")."
                ".$game->match("{$title} - SEMIFINAL 1 - 1º GRUPO 1 x VENCEDOR Q1", "", "", "15px")."
                ".$game->match("{$title} - SEMIFINAL 2 - 1º GRUPO 2 x VENCEDOR Q2", "", "", "15px")."
                ".$game->match("{$title} - FINAL - VENCEDOR S1 x VENCEDOR S2", "", "", "15px")."
            </div>
        ";
    } elseif ($total == 12) {
         $html .= "
            <div style='page-break-inside: avoid; margin-bottom: 25px;'>
                ".$game->match("{$title} - QUARTA 1 - 1º GRUPO 1 x 2º GRUPO 4", "", "", "15px")."
                ".$game->match("{$title} - QUARTA 2 - 1º GRUPO 2 x 2º GRUPO 3", "", "", "15px")."
                ".$game->match("{$title} - QUARTA 3 - 1º GRUPO 3 x 2º GRUPO 2", "", "", "15px")."
                ".$game->match("{$title} - QUARTA 4 - 1º GRUPO 4 x 2º GRUPO 1", "", "", "15px")."
                ".$game->match("{$title} - SEMIFINAL 1 - VENCEDOR Q1 x VENCEDOR Q2", "", "", "15px")."
                ".$game->match("{$title} - SEMIFINAL 2 - VENCEDOR Q3 x VENCEDOR Q4", "", "", "15px")."
                ".$game->match("{$title} - FINAL - VENCEDOR S1 x VENCEDOR S2", "", "", "15px")."
            </div>
        ";
    } elseif ($total == 13) {
        $html .= "
            <div style='page-break-inside: avoid; margin-bottom: 25px;'>
                ".$game->match("{$title} - QUARTA 1 - 1º GRUPO 1 x 2º GRUPO 4", "", "", "15px")."
                ".$game->match("{$title} - QUARTA 2 - 1º GRUPO 2 x 2º GRUPO 3", "", "", "15px")."
                ".$game->match("{$title} - QUARTA 3 - 1º GRUPO 3 x 2º GRUPO 1", "", "", "15px")."
                ".$game->match("{$title} - QUARTA 4 - 1º GRUPO 4 x 2º GRUPO 2", "", "", "15px")."
                ".$game->match("{$title} - SEMIFINAL 1 - VENCEDOR Q1 x VENCEDOR Q2", "", "", "15px")."
                ".$game->match("{$title} - SEMIFINAL 2 - VENCEDOR Q3 x VENCEDOR Q4", "", "", "15px")."
                ".$game->match("{$title} - FINAL - VENCEDOR S1 x VENCEDOR S2", "", "", "15px")."
            </div>
        ";
    } elseif ($total == 16) {
        $html .= "
            <div style='page-break-inside: avoid; margin-bottom: 25px;'>
                ".$game->match("{$title} - OITAVA 1 - 2º GRUPO 2 x 2º GRUPO 5", "", "", "15px")."
                ".$game->match("{$title} - OITAVA 2 - 2º GRUPO 3 x 2º GRUPO 4", "", "", "15px")."
                ".$game->match("{$title} - QUARTA 1 - 1º GRUPO 1 x VENCEDOR O1", "", "", "15px")."
                ".$game->match("{$title} - QUARTA 2 - 1º GRUPO 2 x VENCEDOR O2", "", "", "15px")."
                ".$game->match("{$title} - QUARTA 3 - 1º GRUPO 3 x 1º GRUPO 4", "", "", "15px")."
                ".$game->match("{$title} - QUARTA 4 - 1º GRUPO 5 x 2º GRUPO 1", "", "", "15px")."
                ".$game->match("{$title} - SEMIFINAL 1 - VENCEDOR Q1 x VENCEDOR Q3", "", "", "15px")."
                ".$game->match("{$title} - SEMIFINAL 2 - VENCEDOR Q2 x VENCEDOR Q4", "", "", "15px")."
            </div>
            <div style='page-break-inside: avoid; margin-bottom: 25px;'>
                ".$game->match("{$title} - FINAL - VENCEDOR S1 x VENCEDOR S2", "", "", "15px")."
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