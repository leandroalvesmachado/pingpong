<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once __DIR__ . '/Csv.php';
require_once __DIR__ . '/Game.php';
require 'vendor/autoload.php';

use Dompdf\Dompdf;

try {
    $html = "
        <!DOCTYPE html>
        <html lang='pt-BR'>
            <head>
                <meta charset='UTF-8'>
            </head>
            <body>
                <table style='width: 100%;'>
                    <thead>
                        <tr style='text-align: center;'>
                            <th>
                                3ª Divisão (Categoria F e Iniciantes)
                            </th>
                        </tr>
                    </thead>
                </table>
    ";

    $csv = new Csv(__DIR__ . '/3_divisao.csv', ';');
    $players = $csv->read();
    $total = count($players);

    $html .= "<div>TOTAL DE ATLETAS: {$total}<div>";

    $html .= "<ul>";
    foreach ($players as $player) {
        $player = trim($player);
        $html .= "<li>{$player}</li>";
    }
    $html .= "</ul>";

    // Ordem aleatória
    shuffle($players);

    // Criando grupos
    $game = new Game();
    $groups = $game->groups($players);

    foreach ($groups as $group => $athletes) {
        $html .= "
            <div style='page-break-inside: avoid; margin-bottom: 10px;'>
                <table style='width:100%; border: 1px solid #808080; margin-bottom: 10px; border-radius: 2px;'>
                    <thead style='background-color: #f0f0f0;'>
                        <tr>
                            <th>GRUPO ".($group + 1)."</th>
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
                <div style='page-break-inside: avoid; margin-bottom: 20px;'>
                    ".$game->match("JOGO {$match['jogo']}", $match['jogador1'], $match['jogador2'])."
                </div>
            ";
        }
    }

    $html .= '
            </body>
        </html>
    ';

    if ($total == 12) {
        $html .= "
            <div style='page-break-inside: avoid; margin-bottom: 20px;'>
                ".$game->match("QUARTA 1 - 1º GRUPO 1 x 2º GRUPO 4", "", "")."
                ".$game->match("QUARTA 2 - 1º GRUPO 2 x 2º GRUPO 3", "", "")."
                ".$game->match("QUARTA 3 - 1º GRUPO 3 x 2º GRUPO 1", "", "")."
                ".$game->match("QUARTA 4 - 1º GRUPO 4 x 2º GRUPO 2", "", "")."
                ".$game->match("SEMIFINAL 1 - Q1 x Q2", "", "")."
                ".$game->match("SEMIFINAL 2 - Q3 x Q4", "", "")."
                ".$game->match("FINAL - S1 x S2", "", "")."
            </div>
        ";
    } elseif ($total == 5) {
        // grupo unico
    } elseif ($total == 7) {
        $html .= "
            <div style='page-break-inside: avoid; margin-bottom: 20px;'>
                ".$game->match("SEMIFINAL 1 - 1º GRUPO 1 x 1º GRUPO 2", "", "")."
                ".$game->match("SEMIFINAL 2 - 1º GRUPO 3 x 1º GRUPO 4", "", "")."
                ".$game->match("FINAL - S1 x S2", "", "")."
            </div>
        ";
    }

    $dompdf = new Dompdf();
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    $dompdf->stream("3_divisao.pdf", ['Attachment' => false]);
} catch (Exception $e) {
    echo "Erro: " . $e->getMessage();
}