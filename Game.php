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
    public function grupos(array $players): array
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

        // Ordena: menores primeiro, maiores no final
        usort($groups, function ($a, $b) {
            return count($a) <=> count($b);
        });

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
                        // 'jogo' => $gameNumber++,
                        'grupo' => chr(65 + $groupIndex),
                        'jogador1' => $players[$i],
                        'jogador2' => $players[$j],
                    ];
                }
            }

            $matchesByGroup[$groupIndex + 1] = $matches;
        }

        return $matchesByGroup;
    }

    public function gruposHtml($groups): string
    {
        $html = "
            <!--
            <div style='font-size: 12px; text-transform: uppercase; font-weight: bold; text-align: center; margin-bottom: 2px;'>
                Grupos
            </div>
            -->
        ";

        $html .= "<table style='width: 100%; border-collapse: collapse;'>\n<tr>";

        $count = 0;
        foreach ($groups as $group => $athletes) {
            $html .= "<td style='width: 30%; vertical-align: top;'>";

            $html .= "
                <table style='width: 100%; border-collapse: collapse; border: 1px solid #000000; margin-bottom: 5px; text-transform: uppercase;'>
                    <thead>
                        <tr>
                            <th style='text-align: center; font-size: 12px; background: #f0f0f0; border: 1px solid #000000; padding: 6px;'>
                                GRUPO ".chr(65 + $group)."
                            </th>
                        </tr>
                    </thead>
                    <tbody>
            ";

            foreach ($athletes as $index => $opponent) {
                $html .= "
                        <tr>
                            <td style='border: 1px solid #000000; padding: 2px; font-size: 11px; text-align: center;'>
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

    public function order(array $matchesByGroup)
    {
        try {
            foreach ($matchesByGroup as $key => $jogos) {
                $totalJogos = count($jogos);
                $grupo = $jogos[0]['grupo'];
                $jogadores = [];

                // Extrai jogadores únicos
                $jogadores = [];
                foreach ($jogos as $jogo) {
                    $jogadores[] = trim($jogo['jogador1']);
                    $jogadores[] = trim($jogo['jogador2']);
                }
                $jogadores = array_values(array_unique($jogadores));

                // Grupo com 3 jogadores
                if ($totalJogos === 3 && count($jogadores) === 3) {
                    $grupos[$key] = [
                        [
                            'grupo' => $grupo,
                            'jogador1' => $jogadores[0], // 1º
                            'jogador2' => $jogadores[2], // 3º
                        ],
                        [
                            'grupo' => $grupo,
                            'jogador1' => $jogadores[1], // 2º
                            'jogador2' => $jogadores[2], // 3º
                        ],
                        [
                            'grupo' => $grupo,
                            'jogador1' => $jogadores[0], // 1º
                            'jogador2' => $jogadores[1], // 2º
                        ],
                    ];
                }

                // Grupo com 4 jogadores
                if ($totalJogos === 6 && count($jogadores) === 4) {
                    $grupos[$key] = [
                        // Rodada 1
                        ['grupo'=>$grupo,'jogador1'=>$jogadores[0],'jogador2'=>$jogadores[3]],
                        ['grupo'=>$grupo,'jogador1'=>$jogadores[1],'jogador2'=>$jogadores[2]],

                        // Rodada 2
                        ['grupo'=>$grupo,'jogador1'=>$jogadores[0],'jogador2'=>$jogadores[2]],
                        ['grupo'=>$grupo,'jogador1'=>$jogadores[3],'jogador2'=>$jogadores[1]],

                        // Rodada 3
                        ['grupo'=>$grupo,'jogador1'=>$jogadores[0],'jogador2'=>$jogadores[1]],
                        ['grupo'=>$grupo,'jogador1'=>$jogadores[2],'jogador2'=>$jogadores[3]],
                    ];
                }
            }

            return $grupos;
        } catch (\Exception $e) {
            print_r($e->getMessage());
            exit;
        }
    }

    public function orderFinal(array $grupos): array
    {
        $rodadas = [];

        foreach ($grupos as $grupo) {

            $totalJogos = count($grupo);

            // Grupo de 3 jogadores (3 jogos)
            if ($totalJogos === 3) {

                $rodadas[0][] = $grupo[0];
                $rodadas[1][] = $grupo[1];
                $rodadas[2][] = $grupo[2];
            }

            // Grupo de 4 jogadores (6 jogos)
            if ($totalJogos === 6) {

                $rodadas[0][] = $grupo[0];
                $rodadas[0][] = $grupo[1];

                $rodadas[1][] = $grupo[2];
                $rodadas[1][] = $grupo[3];

                $rodadas[2][] = $grupo[4];
                $rodadas[2][] = $grupo[5];
            }
        }

        // Junta todas rodadas numa ordem única
        $ordemFinal = [];

        foreach ($rodadas as $rodada) {
            foreach ($rodada as $jogo) {
                $ordemFinal[] = $jogo;
            }
        }

        return $ordemFinal;
    }

    public function combates($partidas, $titulo, $total): string
    {
        $jogo = 1;
        $html = "";

        foreach ($partidas as $partida) {
            $html .= "<div style='page-break-inside: avoid; margin-bottom: 2px;'>";
            $html .= $this->combateHtml("Jogo {$jogo} - {$titulo} - GRUPO {$partida['grupo']}", $partida['jogador1'], $partida['jogador2']);
            $html .= "</div>";

            $jogo++;
        }

        $jogos = $this->jogosEliminatorias($total);

        foreach ($jogos as $partida) {
            $html .= "<div style='page-break-inside: avoid; margin-bottom: 2px;'>";
            $html .= $this->combateHtml("Jogo {$jogo} - {$titulo} - {$partida}", "", "");
            $html .= "</div>";

            $jogo++;
        }

        return $html;
    }

    public function combateHtml($game, $player1, $player2, $padding = "1px"): string
    {
        return "
            <table style='width:100%; border-collapse: collapse; border: 0px solid #000000; border-radius: 2px;'>
                <tbody>
                    <tr>
                        <td width='50%' style='padding: {$padding}; text-align: left; font-size: 11px; border: 0px solid #000000; text-transform: uppercase;'>{$game}</td>
                        <td width='20%' style='padding: {$padding}; text-align: right; font-size: 10px; border: 0px solid #000000; text-transform: uppercase;'>{$player1}</td>
                        <td width='30px' height='40px' style='border:1px solid #000000;'></td>
                        <td width='2%' style='text-align: center;'>x</td>
                        <td width='30px' height='40px' style='border:1px solid #000000;'></td>
                        <td style='padding: {$padding}; text-align: left; font-size: 10px; border: 0px solid #000000; text-transform: uppercase;'>{$player2}</td>
                    </tr>
                </tbody>
            </table>
        ";
    }

    public function sumulasGrupos($gamesOrderFinal, $title): string
    {
        $html = "";
        $contador = 0;

        foreach ($gamesOrderFinal as $index => $match) {
            if ($contador == 7) {
                $html .= "<div style='page-break-before: always;'></div>";
                $contador = 0;
            }

            $html .= "
                <div style='page-break-inside: avoid; margin-bottom: 25px;'>
                    ".$this->partidaHtml("Jogo ".($index + 1)." - {$title} - GRUPO {$match['grupo']}", $match['jogador1'], $match['jogador2'])."
                </div>
            ";

            $contador++;
        }

        return $html;
    }

    public function jogosEliminatorias(int $total)
    {
        $jogos = match (true) {
            $total == 4 || $total == 5 => [
            ],
            $total == 7 || $total == 8 => [
                "SEMIFINAL 1 - 1º GRUPO A x 2º GRUPO B",
                "SEMIFINAL 2 - 1º GRUPO B x 2º GRUPO A",
                "FINAL - VENCEDOR SEMIFINAL 1 x VENCEDOR SEMIFINAL 2"
            ],
            $total == 9 => [
                "SEMIFINAL 1 - 1º GRUPO A x 2º GRUPO B",
                "SEMIFINAL 2 - 1º GRUPO B x 2º GRUPO A",
                "FINAL - VENCEDOR SEMIFINAL 1 x VENCEDOR SEMIFINAL 2"
            ],
            $total == 10 || $total == 11 => [
                "QUARTA 1 - 2º GRUPO B x 2º GRUPO C",
                "QUARTA 2 - 2º GRUPO A x 1º GRUPO C",
                "SEMIFINAL 1 - 1º GRUPO A x VENCEDOR QUARTA 1",
                "SEMIFINAL 2 - 1º GRUPO B x VENCEDOR QUARTA 2",
                "FINAL - VENCEDOR SEMIFINAL 1 x VENCEDOR SEMIFINAL 2"
            ],
            $total == 12 => [
                "QUARTA 1 - 1º GRUPO A x 2º GRUPO D",
                "QUARTA 2 - 1º GRUPO B x 2º GRUPO C",
                "QUARTA 3 - 1º GRUPO C x 2º GRUPO B",
                "QUARTA 4 - 1º GRUPO D x 2º GRUPO A",
                "SEMIFINAL 1 - VENCEDOR QUARTA 1 x VENCEDOR QUARTA 2",
                "SEMIFINAL 2 - VENCEDOR QUARTA 3 x VENCEDOR QUARTA 4",
                "FINAL - VENCEDOR SEMIFINAL 1 x VENCEDOR SEMIFINAL 2"
            ],
            $total == 13 || $total == 14 => [
                "QUARTA 1 - 1º GRUPO A x 2º GRUPO E",
                "QUARTA 2 - 1º GRUPO B x 2º GRUPO C",
                "QUARTA 3 - 1º GRUPO C x 2º GRUPO A",
                "QUARTA 4 - 1º GRUPO D x 2º GRUPO B",
                "SEMIFINAL 1 - VENCEDOR QUARTA 1 x VENCEDOR QUARTA 2",
                "SEMIFINAL 2 - VENCEDOR QUARTA 3 x VENCEDOR QUARTA 4",
                "FINAL - VENCEDOR SEMIFINAL 1 x VENCEDOR SEMIFINAL 2"
            ],
            $total == 15 => [
                "OITAVA 1 - 2º GRUPO B x 2º GRUPO E",
                "OITAVA 2 - 2º GRUPO C x 2º GRUPO D",
                "QUARTA 1 - 1º GRUPO A x VENCEDOR OITAVA 1",
                "QUARTA 2 - 1º GRUPO B x VENCEDOR OITAVA 2",
                "QUARTA 3 - 1º GRUPO C x 1º GRUPO D",
                "QUARTA 4 - 1º GRUPO E x 2º GRUPO A",
                "SEMIFINAL 1 - VENCEDOR QUARTA 1 x VENCEDOR QUARTA 3",
                "SEMIFINAL 2 - VENCEDOR QUARTA 2 x VENCEDOR QUARTA 4",
                "FINAL - VENCEDOR SEMIFINAL 1 x VENCEDOR SEMIFINAL 2"
            ],
            $total == 16 || $total == 17 => [
                "OITAVA 1 - 2º GRUPO B x 2º GRUPO E",
                "OITAVA 2 - 2º GRUPO C x 2º GRUPO D",
                "QUARTA 1 - 1º GRUPO A x VENCEDOR OITAVA 1",
                "QUARTA 2 - 1º GRUPO B x VENCEDOR OITAVA 2",
                "QUARTA 3 - 1º GRUPO C x 1º GRUPO D",
                "QUARTA 4 - 1º GRUPO E x 2º GRUPO A",
                "SEMIFINAL 1 - VENCEDOR QUARTA 1 x VENCEDOR QUARTA 3",
                "SEMIFINAL 2 - VENCEDOR QUARTA 2 x VENCEDOR QUARTA 4",
                "FINAL - VENCEDOR SEMIFINAL 1 x VENCEDOR SEMIFINAL 2"
            ],
            $total == 18 => [
                "OITAVA 1 - 1º GRUPO E x 2º GRUPO C",
                "OITAVA 2 - 1º GRUPO F x 2º GRUPO D",
                "OITAVA 3 - 2º GRUPO A x 2º GRUPO E",
                "OITAVA 4 - 2º GRUPO B x 2º GRUPO F",
                "QUARTA 1 - 1º GRUPO 1 x VENCEDOR OITAVA 1",
                "QUARTA 2 - 1º GRUPO 2 x VENCEDOR OITAVA 2",
                "QUARTA 3 - 1º GRUPO 3 x VENCEDOR OITAVA 3",
                "QUARTA 4 - 1º GRUPO 4 x VENCEDOR OITAVA 4",
                "SEMIFINAL 1 - VENCEDOR QUARTA 1 x VENCEDOR QUARTA 3",
                "SEMIFINAL 2 - VENCEDOR QUARTA 2 x VENCEDOR QUARTA 4",
                "FINAL - VENCEDOR SEMIFINAL 1 x VENCEDOR SEMIFINAL 2"
            ],
        };

        return $jogos;
    }

    public function sumulasEliminatorias(int $total, string $title): string
    {
        $jogos = $this->jogosEliminatorias($total);
        $contador = 0;
        $html = "<div style='page-break-inside: avoid; margin-bottom: 25px;'>";

        foreach ($jogos as $descricao) {
            if ($contador == 8) {
                $html .= "<div style='page-break-before: always;'></div>";
                $contador = 0;
            }

            $html .= $this->partidaHtml("{$title} - {$descricao}", "", "", "15px");

            $contador++;
        }

        $html .= "</div>";

        return $html;
    }

    public function partidaHtml($game, $player1, $player2, $padding = "5px"): string
    {
        return "
            <table style='width:100%; border-collapse: collapse; border: 1px solid #000000; margin-bottom: 10px; border-radius: 2px; text-transform: uppercase;'>
                <thead>
                    <tr style='border: 1px solid #000000;'>
                        <th colspan='7' style='font-size: 12.5px; background: #f0f0f0;'>{$game}</th>
                    </tr>
                    <tr style='text-align: center; font-size: 12.5px;'>
                        <th width='25%' style='padding: 5px; border: 1px solid #000000;'>ATLETAS</th>
                        <th style='border: 1px solid #000000;'>1º SET</th>
                        <th style='border: 1px solid #000000;'>2º SET</th>
                        <th style='border: 1px solid #000000;'>3º SET</th>
                        <th style='border: 1px solid #000000;'>4º SET</th>
                        <th style='border: 1px solid #000000;'>5º SET</th>
                        <th width='20%' style='border: 1px solid #000000;'>FINAL</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style='padding: {$padding}; text-align: left; font-size: 12.5px; border: 1px solid #000000;'>{$player1}</td>
                        <td style='border: 1px solid #000000;'></td>
                        <td style='border: 1px solid #000000;'></td>
                        <td style='border: 1px solid #000000;'></td>
                        <td style='border: 1px solid #000000;'></td>
                        <td style='border: 1px solid #000000;'></td>
                        <td style='border: 1px solid #000000;'></td>
                    </tr>
                    <tr>
                        <td style='padding: {$padding}; text-align: left; font-size: 12.5px; border: 1px solid #000000;'>{$player2}</td>
                        <td style='border: 1px solid #000000;'></td>
                        <td style='border: 1px solid #000000;'></td>
                        <td style='border: 1px solid #000000;'></td>
                        <td style='border: 1px solid #000000;'></td>
                        <td style='border: 1px solid #000000'></td>
                        <td style='border: 1px solid #000000'></td>
                    </tr>
                </tbody>
            </table>
        ";
    }
}