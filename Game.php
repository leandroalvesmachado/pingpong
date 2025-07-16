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

    public function match($game, $player1, $player2, $padding = "5px"): string
    {
        return "
            <table style='width:100%; border: 1px solid #808080; margin-bottom: 10px; border-radius: 2px;'>
                <thead>
                    <tr>
                        <th width='25%' style='padding: 5px; text-align: left; font-size: 13px;'>{$game}</th>
                        <th style='text-align: center; border: 1px solid; font-size: 12px;'>1º SET</th>
                        <th style='text-align: center; border: 1px solid; font-size: 12px;'>2º SET</th>
                        <th style='text-align: center; border: 1px solid; font-size: 12px;'>3º SET</th>
                        <th style='text-align: center; border: 1px solid; font-size: 12px;'>4º SET</th>
                        <th style='text-align: center; border: 1px solid; font-size: 12px;'>5º SET</th>
                        <th width='20%' style='text-align: center; border: none;'>FINAL</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style='padding: {$padding}; text-align: left;'>{$player1}</td>
                        <td style='border-bottom: 1px solid black; text-align: center; border-left: 1px solid;'></td>
                        <td style='border-bottom: 1px solid black; text-align: center; border-left: 1px solid;'></td>
                        <td style='border-bottom: 1px solid black; text-align: center; border-left: 1px solid;'></td>
                        <td style='border-bottom: 1px solid black; text-align: center; border-left: 1px solid;'></td>
                        <td style='border-bottom: 1px solid black; text-align: center; border-left: 1px solid; border-right: 1px solid;'></td>
                    </tr>
                    <tr>
                        <td style='padding: {$padding}; text-align: left;'>{$player2}</td>
                        <td style='text-align: center; border-left: 1px solid;'></td>
                        <td style='text-align: center; border-left: 1px solid;'></td>
                        <td style='text-align: center; border-left: 1px solid;'></td>
                        <td style='text-align: center; border-left: 1px solid;'></td>
                        <td style='text-align: center; border-left: 1px solid; border-right: 1px solid;'></td>
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

    
}