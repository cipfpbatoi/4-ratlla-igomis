<?php

namespace Joc4enRatlla\Models;



class Board
{
    public const FILES = 6;
    public const COLUMNS = 7;
    public const DIRECTIONS = [
        [0, 1],   // Horizontal derecha
        [1, 0],   // Vertical abajo
        [1, 1],   // Diagonal abajo-derecha
        [1, -1]   // Diagonal abajo-izquierda
    ];

    private array $slots;

    public function __construct()
    {
        $this->slots = self::initializeBoard();
    }

    public function getSlots(): array
    {
        return $this->slots;
    }

    private static function initializeBoard(): array
    {
        $slots = [];
        for ($i = 1; $i <= self::FILES; $i++) {
            for ($j = 1; $j <= self::COLUMNS; $j++) {
                $slots[$i][$j] = 0;
            }
        }
        return $slots;
    }

    public function setMovementOnBoard(int $column, int $player): array
    {
        $row = -1;
        for ($i = self::FILES  ; $i >  0; $i--) {
            if ($this->slots[$i][$column] === 0) {
                $this->slots[$i][$column] = $player;
                $row = $i;
                break;
            }
        }
        return [$row, $column];
    }

    public function checkWin(array $coord): bool
    {
        $x = $coord[0];
        $y = $coord[1];
        $player = $this->slots[$x][$y];

        foreach (self::DIRECTIONS as $direction) {
            $count = 1;
            // Contar en una dirección
            $count += $this->countDirection($x, $y, $direction, $player);
            // Contar en la dirección opuesta
            $count += $this->countDirection($x, $y, [-$direction[0], -$direction[1]], $player);

            if ($count >= 4) {
                return true; // Se encontraron 4 en línea
            }
        }

        return false;
    }

    private function countDirection(int $x, int $y, array $direction, int $player): int
    {
        $dx = $direction[0];
        $dy = $direction[1];
        $count = 0;

        for ($i = 1; $i < 4; $i++) {
            $nx = $x + $i * $dx;
            $ny = $y + $i * $dy;

            if ($nx >  0 && $nx <= self::FILES && $ny >  0 && $ny <= self::COLUMNS && $this->slots[$nx][$ny] === $player) {
                $count++;
            } else {
                break;
            }
        }

        return $count;
    }

    public function isValidMove(int $column): bool
    {
        return $this->slots[1][$column] === 0;
    }


}
