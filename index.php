<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once __DIR__ . '/Csv.php';
require_once __DIR__ . '/Game.php';
require 'vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

try {
    $csv = new Csv(__DIR__ . '/2_divisao.csv', ';');
    $players = $csv->read();
    $total = count($players);
    // echo $total . PHP_EOL;

    // Ordem aleatória
    shuffle($players);

    // echo"<pre>";
    // print_r($players);
    // echo"</pre>";

    $game = new Game();

    if ($total == 9) {
        // Divide em grupos de 3
        $groups = $game->groups($players, 3);

        echo"<pre>";
        print_r($groups);
        echo"</pre>";

        $matches = $game->matches($groups);

        echo"<pre>";
        print_r($matches);
        echo"</pre>";

        $html = '<h1>Jogos</h1>';

        foreach ($matches as $group => $games) {
            foreach ($games as $match) {
                $html .= "<div style='border: 1px solid #000000;'>";
                $html .= "  2ª DIVISÃO (CATEGORIAS D e E) - {$group}";
                $html .= "  <br>";
                $html .= "  Jogo {$match['jogo']}";
                $html .= "</div>";
            }
            
            // $html .= "<h2>Grupo " . ($group + 1) . "</h2>";
            // foreach ($games as $match) {
            //     $html .= "
            //     <div style='border: 1px solid #000; padding: 10px; margin-bottom: 20px;'>
            //         <h3>Jogo {$match['jogo']}: {$match['jogador 1']} vs {$match['jogador 2']}</h3>
            //         <table border='1' cellpadding='5' cellspacing='0' style='border-collapse: collapse; width: 100%; text-align: center;'>
            //             <thead>
            //                 <tr>
            //                     <th>Set</th>
            //                     <th>{$match['jogador 1']}</th>
            //                     <th>{$match['jogador 2']}</th>
            //                 </tr>
            //             </thead>
            //             <tbody>";
            //     for ($set = 1; $set <= 5; $set++) {
            //         $html .= "
            //                 <tr>
            //                     <td>Set $set</td>
            //                     <td>_____</td>
            //                     <td>_____</td>
            //                 </tr>";
            //     }
            //     $html .= "
            //             </tbody>
            //         </table>
            //         <p><strong>Vencedor:</strong> __________________</p>
            //         <p><strong>Placar Final (Sets):</strong> ____ x ____</p>
            //     </div>";
            // }
        }

        echo $html;

        // $dompdf = new Dompdf();
        // $dompdf->loadHtml('hello world');
        // $dompdf->setPaper('A4', 'landscape');
        // $dompdf->render();
        // $dompdf->stream();

    }

} catch (Exception $e) {
    echo "Erro: " . $e->getMessage();
}