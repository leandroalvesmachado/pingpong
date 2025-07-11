<?php

class Game
{
    /**
     * Divide os jogadores em grupos de tamanho fixo.
     *
     * @param array $players Lista de jogadores
     * @param int $groupSize Tamanho de cada grupo
     * @return array Grupos de jogadores
     */
    public function groups(array $players, int $groupSize): array
    {
        return array_chunk($players, $groupSize);
    }

    /**
     * Gera partidas de todos contra todos para cada grupo, com id sequencial.
     *
     * @param array $groups Array de grupos, cada grupo é um array de jogadores.
     * @return array Partidas organizadas por grupo com id.
     */
    public function matches(array $groups): array
    {
        $matchesByGroup = [];
        $gameNumber = 1;

        foreach ($groups as $groupIndex => $players) {
            $matches = [];

            $numPlayers = count($players);
            for ($i = 0; $i < $numPlayers; $i++) {
                for ($j = $i + 1; $j < $numPlayers; $j++) {
                    $matches[] = [
                        'jogo' => $gameNumber++,
                        'jogador1' => $players[$i],
                        'jogador2' => $players[$j],
                    ];
                }
            }

            $matchesByGroup[$groupIndex] = $matches;
        }

        return $matchesByGroup;
    }
}