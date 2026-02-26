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

    $game = new Game();
    $csv = new Csv(__DIR__ . "/{$file}", ';');
    $players = $csv->read();
    $total = count($players);
    $prize = $game->prize($total);
    
    $html = "
        <!DOCTYPE html>
        <html lang='pt-BR'>
            <head>
                <meta charset='UTF-8'>
            </head>
            <body>
                <table style='width:100%; font-size: 12px; text-transform: uppercase;'>
                    <tr>
                        <th>{$title} - SORTEIO REALIZADO EM ".date('d/m/Y H:i:s')."</th>
                    </tr>
                </table>
    ";

    $html .= "
        <div style='font-size: 12px; text-transform: uppercase; margin-bottom: 2px; margin-top: 2px; text-align: center;'>
            <strong>TOTAL DE ATLETAS:</strong> {$total} <!-- (1º lugar: 40% do total / 2º lugar: 30% do total / 3º lugar (cada): 15% do total) -->
        </div>
        <!--
        <div style='font-size: 11px; text-transform: uppercase; text-align: justify; margin-bottom: 2px;'>
            ".implode(', ', array_map('trim', $players))."
        </div>
        -->
    ";

    // Ordem aleatória dos atletas
    shuffle($players);

    // Criando grupos
    $grupos = $game->grupos($players);

    // Criando HTML dos Grupos
    $html .= $game->gruposHtml($grupos);

    // Gerando partidas e Ordem do jogos (Melhorar)    
    $partidas = $game->matches($grupos);
    $partidasOrdem = $game->order($partidas);
    $partidasOrdemFinal = $game->orderFinal($partidasOrdem);

    // Combates
    $html .= $game->combates($partidasOrdemFinal, $title, $total);

    // Quebrando pagina
    $html .= "<div style='page-break-before: always;'>";

    // Sumulas (grupos)
    $html .= $game->sumulasGrupos($partidasOrdemFinal, $title);

    // Sumulas (eliminatorias)
    $html .= $game->sumulasEliminatorias($total, $title);

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
