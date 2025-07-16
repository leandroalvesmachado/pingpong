<?php

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
            $title = '1ª Divisão (Categorias A, B e C)';
            break;
        case '2_divisao.csv':
            $pdf = '2_divisao';
            $title = '2ª Divisão (Categorias D e E)';
            break;
        case '3_divisao.csv':
            $pdf = '3_divisao';
            $title = '3ª Divisão (Categoria F e Iniciantes)';
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
                <table style='width:100%;'>
                    <tr>
                        <th>{$title}</th>
                    </tr>
                </table>
    ";

    $game = new Game();
    $csv = new Csv(__DIR__ . "/{$file}", ';');
    $players = $csv->read();
    $total = count($players);
    $prize = $game->prize($total);

    $html .= "<div>TOTAL DE ATLETAS: {$total}</div>";
    
    $html .= "<ul>";
    foreach ($players as $player) {
        $player = trim($player);
        $html .= "<li>{$player}</li>";
    }
    $html .= "</ul>";

    $html .= "<div>TOTAL ARRECADADO: R$ ".$prize['total_arrecadado']."</div><br>";
    $html .= "<div>1º lugar: R$ ".$prize['1º lugar']."</div>";
    $html .= "<div>2º lugar: R$ ".$prize['2º lugar']."</div>";
    $html .= "<div>3º lugar (cada): R$ ".$prize['3º lugar (cada)']."</div><br>";


    // Ordem aleatória
    shuffle($players);

    // Criando grupos
    $groups = $game->groups($players);

    foreach ($groups as $group => $athletes) {
        $html .= "
            <div style='page-break-inside: avoid; margin-bottom: 15px;'>
                <table style='width:100%; border: 1px solid #808080; margin-bottom: 10px; border-radius: 2px;'>
                    <thead>
                        <tr>
                            <th style='border-bottom: 1px solid black;'>GRUPO ".($group + 1)."</th>
                        </tr>
                    </thead>
        ";
        foreach ($athletes as $athlete) {
            $html .= "
                    <tbody>
                        <tr>
                            <td>{$athlete}</td>
                        </tr>
                    </tbody>
            ";
        }

        $html.= "
                </table>
            </div>
        ";
    }

    // Gerando partidas
    $matches = $game->matches($groups);

    foreach ($matches as $group => $games) {
        foreach ($games as $match) {
            $html .= "
                <div style='page-break-inside: avoid; margin-bottom: 25px;'>
                    ".$game->match("JOGO {$match['jogo']}", $match['jogador1'], $match['jogador2'])."
                </div>
            ";
        }
    }

    $html .= '
            </body>
        </html>
    ';

    if ($total == 4 || $total == 5) {
        // grupo unico
    } elseif ($total == 6) {
    } elseif ($total == 7) {
        $html .= "
            <div style='page-break-inside: avoid; margin-bottom: 25px;'>
                ".$game->match("SEMIFINAL 1 - 1º GRUPO 1 x 2º GRUPO 2", "", "", "15px")."
                ".$game->match("SEMIFINAL 2 - 1º GRUPO 2 x 2º GRUPO 1", "", "", "15px")."
                ".$game->match("FINAL - S1 x S2", "", "", "15px")."
            </div>
        ";
    } elseif ($total == 8) {
        $html .= "
            <div style='page-break-inside: avoid; margin-bottom: 25px;'>
                ".$game->match("SEMIFINAL 1 - 1º GRUPO 1 x 2º GRUPO 2", "", "", "15px")."
                ".$game->match("SEMIFINAL 2 - 1º GRUPO 2 x 2º GRUPO 1", "", "", "15px")."
                ".$game->match("FINAL - S1 x S2", "", "", "15px")."
            </div>
        ";
    } elseif ($total == 12) {
         $html .= "
            <div style='page-break-inside: avoid; margin-bottom: 25px;'>
                ".$game->match("QUARTA 1 - 1º GRUPO 1 x 2º GRUPO 4", "", "", "15px")."
                ".$game->match("QUARTA 2 - 1º GRUPO 2 x 2º GRUPO 3", "", "", "15px")."
                ".$game->match("QUARTA 3 - 1º GRUPO 3 x 2º GRUPO 1", "", "", "15px")."
                ".$game->match("QUARTA 4 - 1º GRUPO 4 x 2º GRUPO 2", "", "", "15px")."
                ".$game->match("SEMIFINAL 1 - Q1 x Q2", "", "", "15px")."
                ".$game->match("SEMIFINAL 2 - Q3 x Q4", "", "", "15px")."
                ".$game->match("FINAL - S1 x S2", "", "", "15px")."
            </div>
        ";
    } elseif ($total == 13) {
        $html .= "
            <div style='page-break-inside: avoid; margin-bottom: 25px;'>
                ".$game->match("QUARTA 1 - 1º GRUPO 1 x 2º GRUPO 4", "", "", "15px")."
                ".$game->match("QUARTA 2 - 1º GRUPO 2 x 2º GRUPO 3", "", "", "15px")."
                ".$game->match("QUARTA 3 - 1º GRUPO 3 x 2º GRUPO 1", "", "", "15px")."
                ".$game->match("QUARTA 4 - 1º GRUPO 4 x 2º GRUPO 2", "", "", "15px")."
                ".$game->match("SEMIFINAL 1 - Q1 x Q2", "", "", "15px")."
                ".$game->match("SEMIFINAL 2 - Q3 x Q4", "", "", "15px")."
                ".$game->match("FINAL - S1 x S2", "", "", "15px")."
            </div>
        ";
    }

    $dompdf = new Dompdf();
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    $dompdf->stream("{$pdf}.pdf", ['Attachment' => false]);
} catch (Exception $e) {
    echo "Erro: " . $e->getMessage();
}