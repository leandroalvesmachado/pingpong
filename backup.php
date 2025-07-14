if ($total == 9) {
        // Divide em grupos de 3
        $groups = $game->groups($players, 3);

        echo"<pre>";
        print_r($groups);
        echo"</pre>";

        exit;

        $matches = $game->matches($groups);

        echo"<pre>";
        print_r($matches);
        echo"</pre>";

        $html = "";
        // $html .= "
        
        // <div class='match-card' style='border:1px solid #ccc; margin-bottom:20px; padding:10px;'>
        //     <div class='match-header' style='display:flex; justify-content:space-between;'>
        //         <div><strong>VETERANO 40 MAS - Grupo 01</strong></div>
        //         <div style='color:#018777; font-weight:bold;'>0-3</div>
        //     </div>

        //     <div class='match-info' style='display:flex; justify-content:space-between; font-size:small; color:gray;'>
        //         <div>Jogo 001 - Mesa 6 - 08:25</div>
        //         <div>21/06/2025</div>
        //     </div>

        //     <hr style='border: 0; border-top: 2px solid #018777; margin:10px 0;'>

        //     <div class='player-result' style='display:flex; justify-content:space-between;'>
        //         <div style='color:#777;'>ANDRÉ FREITAS</div>
        //         <div style='display:flex; gap:10px;'>
        //         <span>0</span>
        //         <span>8</span>
        //         <span>4</span>
        //         <span>7</span>
        //         </div>
        //     </div>

        //     <div class='player-result' style='display:flex; justify-content:space-between; margin-top:10px;'>
        //         <div style='color:#018777; font-weight:bold;'>VICTOR CARVALHO</div>
        //         <div style='display:flex; gap:10px; font-weight:bold;'>
        //         <span>3</span>
        //         <span>11</span>
        //         <span>11</span>
        //         <span>11</span>
        //         </div>
        //     </div>
        // </div>
        // ";

        $counter = 0;

        foreach ($matches as $group => $games) {
            foreach ($games as $match) {
                if ($counter % 3 === 0) {
                    $html .= "<div style='display: flex; gap: 10px; margin-bottom: 30px;'>";
                }

                $html .= "<div style='border: 1px solid #ccc; margin-bottom: 20px; padding: 10px;'>";
                $html .= "  <div style='display:flex; justify-content:space-between;'>";
                $html .= "      <div><strong>2ª DIVISÃO (CATEGORIAS D e E) - <br> GRUPO {$group}</strong></div>";
                $html .= "  </div>";
                $html .= "</div>";

                $counter++;

                if ($counter % 3 === 0 || ($counter === count($games))) {
                    $html .= "</div>";  // fecha a div de 3 jogos
                }

                // $html .= "<div style='display: flex; border-bottom: 1px solid blue;'>";
                // $html .= "  <div>";
                // $html .= "      <span style='font-weight: bold;'>2ª DIVISÃO (CATEGORIAS D e E) - <br> GRUPO {$group}</span>";
                // $html .= "      <br>";
                // $html .= "      <div style='display: flex; border-bottom: 1px solid blue;'>";
                // $html .= "          <div style='width: 50%; padding: 10px; text-align: left;'>";
                // $html .= "              Jogo {$match['jogo']}";
                // $html .= "          </div>";
                // $html .= "          <div style='width: 50%; padding: 10px; text-align: right;'>";
                // $html .= "              19/07/2025";
                // $html .= "          </div>";
                // $html .= "      </div>";
                // $html .= "      <div style='width: 50%; padding: 10px;'>";
                // $html .= "          {$match['jogador1']}";
                // $html .= "      </div>";
                // $html .= "      <div style='width: 50%; padding: 10px;'>";
                // $html .= "          {$match['jogador2']}";
                // $html .= "      </div>";
                // $html .= "  </div>";
                // $html .= "</div>";
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