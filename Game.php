<?php

class Game
{
    /**
     * Divide os jogadores em grupos de 3 prioritariamente,
     * ajustando para grupos de 4 se necessário.
     *
     * @param array $players Lista de jogadores
     * @return array Grupos de jogadores
     */
    public function groups(array $players): array
    {
        $total = count($players);
        $groups = [];

        if ($total < 3) {
            return [$players]; // Apenas um grupo se houver menos de 3
        }

        if ($total == 5) {
            return [$players]; // Apenas um grupo se houver 5
        }

        $playersCopy = $players;
        $i = 0;

        // Tenta fazer o máximo de grupos de 3
        while (count($playersCopy) >= 3) {
            if (count($playersCopy) === 4) {
                // Se restarem exatamente 4, faz um grupo de 4
                $groups[] = array_splice($playersCopy, 0, 4);
            } else {
                $groups[] = array_splice($playersCopy, 0, 3);
            }
        }

        // Se sobrou 1 ou 2 jogadores, redistribui
        if (count($playersCopy) > 0) {
            foreach ($playersCopy as $player) {
                // Adiciona o jogador ao primeiro grupo com menos de 4
                foreach ($groups as &$group) {
                    if (count($group) < 4) {
                        $group[] = $player;
                        break;
                    }
                }
            }
        }

        return $groups;
    }


    /**
     * Gera as partidas entre todos os jogadores de cada grupo.
     *
     * @param array $groups Lista de grupos (cada grupo é um array de jogadores)
     * @return array Lista de partidas organizadas por grupo
     */
    public function matches(array $groups): array
    {
        $matchesByGroup = [];
        $gameNumber = 1;

        foreach ($groups as $groupIndex => $players) {
            $matches = [];

            $numPlayers = count($players);

            // Todos contra todos
            for ($i = 0; $i < $numPlayers; $i++) {
                for ($j = $i + 1; $j < $numPlayers; $j++) {
                    $matches[] = [
                        'jogo' => $gameNumber++,
                        'grupo' => $groupIndex + 1,
                        'jogador1' => $players[$i],
                        'jogador2' => $players[$j],
                    ];
                }
            }

            $matchesByGroup[$groupIndex + 1] = $matches;
        }

        return $matchesByGroup;
    }

    // public function group($groups): string
    // {
    //     $html = "";

    //     foreach ($groups as $group => $athletes) {
    //         $html .= "
    //             <div style='page-break-inside: avoid; margin-bottom: 15px;'>
    //                 <table style='width: 40%; border-collapse: collapse; border: 1px solid #ccc; margin-bottom: 10px; text-transform: uppercase;'>
    //                     <thead>
    //                         <tr>
    //                             <th style='text-align: center; font-size: 12.5px; background: #f0f0f0; border: 1px solid #ccc; padding: 8px;'>
    //                                 GRUPO ".($group + 1)."
    //                             </th>
    //                         </tr>
    //                     </thead>
    //                     <tbody>
    //         ";

    //         foreach ($athletes as $opponent) {
    //             $html .= "
    //                         <tr>
    //                             <td style='border: 1px solid #ccc; padding: 6px; font-size: 11px; text-align: center;'>".htmlspecialchars($opponent)."</td>
    //                         </tr>
    //             ";
    //         }

    //         $html .= "
    //                     </tbody>
    //                 </table>
    //             </div>
    //         ";
    //     }

    //     return $html;
    // }

    public function group($groups): string
    {
        $html = "<table style='width: 100%; border-collapse: collapse;'>\n<tr>";

        $count = 0;
        foreach ($groups as $group => $athletes) {
            $html .= "<td style='width: 30%; vertical-align: top;'>";

            $html .= "
                <table style='width: 100%; border-collapse: collapse; border: 1px solid #ccc; margin-bottom: 10px; text-transform: uppercase;'>
                    <thead>
                        <tr>
                            <th style='text-align: center; font-size: 12.5px; background: #f0f0f0; border: 1px solid #ccc; padding: 8px;'>
                                GRUPO ".($group + 1)."
                            </th>
                        </tr>
                    </thead>
                    <tbody>
            ";

            foreach ($athletes as $opponent) {
                $html .= "
                        <tr>
                            <td style='border: 1px solid #ccc; padding: 6px; font-size: 11px; text-align: center;'>
                                ".htmlspecialchars($opponent)."
                            </td>
                        </tr>
                ";
            }

            $html .= "
                    </tbody>
                </table>
            ";

            $html .= "</td>";

            $count++;

            // Quebra a linha da tabela externa a cada 3 grupos
            if ($count % 3 === 0) {
                $html .= "</tr>\n<tr>";
            }
        }

        $html .= "</tr>\n</table>";

        return $html;
    }

    // public function group($groups): string
    // {
    //     $html = "";

    //     foreach ($groups as $group => $athletes) {
    //         $html .= "
    //             <div style='page-break-inside: avoid; margin-bottom: 15px;'>
    //                 <table style='width: 100%; border-collapse: collapse; border: 1px solid #ccc; margin-bottom: 10px; text-transform: uppercase;'>
    //                     <thead>
    //                         <tr>
    //                             <th colspan='".(count($athletes) + 1)."' style='text-align: center; font-size: 12.5px; background: #f0f0f0; border: 1px solid #ccc; padding: 8px;'>
    //                                 GRUPO ".($group + 1)."
    //                             </th>
    //                         </tr>
    //                         <tr>
    //                             <th style='border: 1px solid #ccc; padding: 6px; font-size: 12px;'>ATLETA</th>";

    //         foreach ($athletes as $opponent) {
    //             $html .= "
    //                 <th style='border: 1px solid #ccc; padding: 6px; font-size: 11px; text-align: center;'>
    //                     ".htmlspecialchars($opponent)."
    //                 </th>";
    //         }

    //         $html .= "
    //                         </tr>
    //                     </thead>
    //                     <tbody>
    //         ";

    //         foreach ($athletes as $i => $athlete) {
    //             $html .= "
    //                 <tr>
    //                     <td style='border: 1px solid #ccc; font-size: 12.5px; padding: 5px;'>".htmlspecialchars($athlete)."</td>";

    //             foreach ($athletes as $j => $opponent) {
    //                 if ($i === $j) {
    //                     $html .= "
    //                         <td style='background: #f9f9f9; border: 1px solid #ccc; text-align: center;'>–</td>";
    //                 } else {
    //                     $html .= "
    //                         <td style='border: 1px solid #ccc; text-align: center;'>
    //                             <input type='checkbox' title='Vitória de ".htmlspecialchars($athlete)." sobre ".htmlspecialchars($opponent)."'>
    //                         </td>";
    //                 }
    //             }

    //             $html .= "</tr>";
    //         }

    //         $html .= "
    //                     </tbody>
    //                 </table>
    //             </div>";
    //     }

    //     return $html;
    // }

    // public function group($groups): string
    // {
    //     $html = "";
        
    //     foreach ($groups as $group => $athletes) {
    //         $html .= "
    //             <div style='page-break-inside: avoid; margin-bottom: 15px;'>
    //                 <table style='width:100%; border: 1px solid #808080; margin-bottom: 10px; border-radius: 2px;'>
    //                     <thead>
    //                         <tr>
    //                             <th style='border-bottom: 1px solid black; font-size: 12.5px;' colspan='3'>GRUPO ".($group + 1)."</th>
    //                         </tr>
    //                         <tr>
    //                             <th width='25%' style='padding: 5px; text-align: left; font-size: 12px;'></th>
    //                             <th style='text-align: center; border: 0px solid; font-size: 12px;'>VITÓRIAS</th>
    //                             <th style='text-align: center; border: 0px solid; font-size: 12px;'>DERROTAS</th>
    //                         </tr>
    //                     </thead>
    //         ";
    //         foreach ($athletes as $athlete) {
    //             $html .= "
    //                     <tbody>
    //                         <tr>
    //                             <td style='font-size: 12.5px;'>{$athlete}</td>
    //                             <td style='text-align: center; border: 0px solid; font-size: 12px;'></td>
    //                             <td style='text-align: center; border: 0px solid; font-size: 12px;'></td>
    //                         </tr>
    //                     </tbody>
    //             ";
    //         }

    //         $html.= "
    //                 </table>
    //             </div>
    //         ";
    //     }

    //     return $html;
    // }

    public function match($game, $player1, $player2, $padding = "5px"): string
    {
        return "
            <table style='width:100%; border-collapse: collapse; border: 1px solid #ccc; margin-bottom: 10px; border-radius: 2px; text-transform: uppercase;'>
                <thead>
                    <tr style='border: 1px solid #ccc;'>
                        <th colspan='7' style='font-size: 12.5px; background: #f0f0f0;'>{$game}</th>
                    </tr>
                    <tr style='text-align: center; font-size: 12.5px;'>
                        <th width='25%' style='padding: 5px; border: 1px solid #ccc;'>ATLETAS</th>
                        <th style='border: 1px solid #ccc;'>1º SET</th>
                        <th style='border: 1px solid #ccc;'>2º SET</th>
                        <th style='border: 1px solid #ccc;'>3º SET</th>
                        <th style='border: 1px solid #ccc;'>4º SET</th>
                        <th style='border: 1px solid #ccc;'>5º SET</th>
                        <th width='20%' style='border: 1px solid #ccc;'>FINAL</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style='padding: {$padding}; text-align: left; font-size: 12.5px; border: 1px solid #ccc;'>{$player1}</td>
                        <td style='border: 1px solid #ccc;'></td>
                        <td style='border: 1px solid #ccc;'></td>
                        <td style='border: 1px solid #ccc;'></td>
                        <td style='border: 1px solid #ccc;'></td>
                        <td style='border: 1px solid #ccc;'></td>
                        <td style='border: 1px solid #ccc;'></td>
                    </tr>
                    <tr>
                        <td style='padding: {$padding}; text-align: left; font-size: 12.5px; border: 1px solid #ccc;'>{$player2}</td>
                        <td style='border: 1px solid #ccc;'></td>
                        <td style='border: 1px solid #ccc;'></td>
                        <td style='border: 1px solid #ccc;'></td>
                        <td style='border: 1px solid #ccc;'></td>
                        <td style='border: 1px solid #ccc'></td>
                        <td style='border: 1px solid #ccc'></td>
                    </tr>
                </tbody>
            </table>
        ";
    }

    public function prize(int $totalCompetidores): array
    {
        $valorInscricao = 25;
        $totalArrecadado = $valorInscricao * $totalCompetidores;

        $primeiroLugar  = round($totalArrecadado * 0.40, 2);
        $segundoLugar   = round($totalArrecadado * 0.30, 2);
        $terceiroLugar  = round($totalArrecadado * 0.15, 2);
        
        return [
            'total_arrecadado' => $totalArrecadado,
            '1º lugar' => $primeiroLugar,
            '2º lugar' => $segundoLugar,
            '3º lugar (cada)' => $terceiroLugar,
            'total_premiado' => $primeiroLugar + $segundoLugar + ($terceiroLugar * 2),
            'restante' => $totalArrecadado - ($primeiroLugar + $segundoLugar + ($terceiroLugar * 2)),
        ];
    }

    public function order(array $matchesByGroup): array
    {
        $ordenado = [];
        $rodada = 0;

        // Descobrir quantas rodadas no máximo há entre todos os grupos
        $maxRodadas = max(array_map('count', $matchesByGroup));

        for ($rodada = 0; $rodada < $maxRodadas; $rodada++) {
            foreach ($matchesByGroup as $grupo => $jogos) {
                if (isset($jogos[$rodada])) {
                    $ordenado[] = $jogos[$rodada];
                }
            }
        }

        // Renumerar os jogos em ordem
        foreach ($ordenado as $i => &$jogo) {
            $jogo['jogo'] = $i + 1;
        }

        // echo"<pre>";
        // print_r($ordenado);

        // Agrupar por grupo
        $grupos = [];
        foreach ($ordenado as $p) {
            $grupos[$p['grupo']][] = $p;
        }

        // Intercalar os jogos (1º jogo de cada grupo, depois 2º, etc)
        $resultado = [];
        $max = max(array_map('count', $grupos)); // máximo de jogos por grupo

        for ($i = 0; $i < $max; $i++) {
            foreach ($grupos as $grupo => $jogos) {
                if (isset($jogos[$i])) {
                    $resultado[] = $jogos[$i];
                }
            }
        }

        // foreach ($resultado as $r) {
        //     echo "Jogo {$r['jogo']} - Grupo {$r['grupo']} - {$r['jogador1']} vs {$r['jogador2']}\n<br>";
        // }

        // exit();

        return $resultado;

        // $jogosPorRodada = [];
        // $maxJogosPorGrupo = 0;

        // // Descobre o maior número de jogos em um grupo
        // foreach ($matchesByGroup as $matches) {
        //     $maxJogosPorGrupo = max($maxJogosPorGrupo, count($matches));
        // }

        // // Para cada rodada (posição), pegamos o i-ésimo jogo de cada grupo
        // for ($i = 0; $i < $maxJogosPorGrupo; $i++) {
        //     foreach ($matchesByGroup as $grupo => $jogos) {
        //         if (isset($jogos[$i])) {
        //             $jogosPorRodada[] = $jogos[$i];
        //         }
        //     }
        // }

        // // Reorganiza os números dos jogos sequencialmente
        // foreach ($jogosPorRodada as $index => &$jogo) {
        //     $jogo['jogo'] = $index + 1;
        // }

        // return $jogosPorRodada;
    }
}