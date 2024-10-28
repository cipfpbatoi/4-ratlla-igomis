<?php

use Joc4enRatlla\Models\Board;
use PHPUnit\Framework\TestCase;

class BoardTest extends TestCase
{
    /**
     * Prova que el tauler s'inicialitza correctament amb valors buits.
     */
    public function testInitializeBoard()
    {
        $board = new Board();
        $slots = $board->getSlots();

        // Comprova que totes les files i columnes estiguin inicialitzades a 0
        for ($i = 1; $i <= Board::FILES; $i++) {
            for ($j = 1; $j <= Board::COLUMNS; $j++) {
                $this->assertEquals(0, $slots[$i][$j]);
            }
        }
    }

    /**
     * Prova que un moviment es pot fer correctament en una columna buida.
     */
    public function testSetMovementOnBoard()
    {
        $board = new Board();

        // Fes un moviment a la columna 3 pel jugador 1
        $coord = $board->setMovementOnBoard(3, 1);

        // Comprova que el moviment ha estat col·locat a la fila més baixa de la columna 3
        $slots = $board->getSlots();
        $this->assertEquals(1, $slots[Board::FILES][3]);
        $this->assertEquals([Board::FILES, 3], $coord);
    }

    /**
     * Prova que un moviment és vàlid quan la columna no està plena.
     */
    public function testIsValidMove()
    {
        $board = new Board();

        // Comprova que un moviment a la columna 3 és vàlid (està buida)
        $this->assertTrue($board->isValidMove(3));

        // Fes un moviment a la columna 3
        $board->setMovementOnBoard(3, 1);

        // La columna encara ha de ser vàlida ja que no està plena
        $this->assertTrue($board->isValidMove(3));
    }

    /**
     * Prova que es detecta una victòria quan es fan 4 moviments consecutius.
     */
    public function testCheckWinHorizontal()
    {
        $board = new Board();

        // Fes quatre moviments consecutius horitzontals pel jugador 1
        $board->setMovementOnBoard(1, 1);
        $board->setMovementOnBoard(2, 1);
        $board->setMovementOnBoard(3, 1);
        $coord = $board->setMovementOnBoard(4, 1);

        // Comprova que el jugador 1 ha guanyat
        $this->assertTrue($board->checkWin($coord));
    }

    /**
     * Prova que no hi ha victòria després de tres moviments consecutius.
     */
    public function testCheckNoWinWithThreeMoves()
    {
        $board = new Board();

        // Fes tres moviments consecutius horitzontals pel jugador 1
        $board->setMovementOnBoard(1, 1);
        $board->setMovementOnBoard(2, 1);
        $coord = $board->setMovementOnBoard(3, 1);

        // Comprova que encara no hi ha victòria
        $this->assertFalse($board->checkWin($coord));
    }

    /**
     * Prova que un moviment no és vàlid si la columna està plena.
     */
    public function testInvalidMoveWhenColumnIsFull()
    {
        $board = new Board();

        // Omple la columna 3
        for ($i = 0; $i < Board::FILES; $i++) {
            $board->setMovementOnBoard(3, 1);
        }

        // Comprova que un moviment a la columna 3 ja no és vàlid
        $this->assertFalse($board->isValidMove(3));
    }
}
