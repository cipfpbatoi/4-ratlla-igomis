<?php

use Joc4enRatlla\Models\Game;
use Joc4enRatlla\Models\Player;
use Joc4enRatlla\Models\Board;
use Joc4enRatlla\Exceptions\IllegalMoveException;
use PHPUnit\Framework\TestCase;

class GameTest extends TestCase
{
    private Player $player1;
    private Player $player2;

    protected function setUp(): void
    {
        $this->player1 = new Player('William', 'Vermell', false);
        $this->player2 = new Player('Holosh', 'Blau', true);
    }

    /**
     * Prova la inicialització del joc.
     */
    public function testInitializeGame()
    {
        $game = new Game($this->player1, $this->player2);

        // Comprova que el tauler està buit
        $this->assertInstanceOf(Board::class, $game->getBoard());

        // Comprova que el proper jugador sigui un dels dos jugadors
        $this->assertContains($game->getNextPlayer(), [1, 2]);

        // Comprova que no hi ha guanyador al principi
        $this->assertNull($game->getWinner());
    }

    /**
     * Prova que es pugui fer un moviment vàlid i que canviï el torn del jugador.
     */
    public function testValidMove()
    {
        $game = new Game($this->player1, $this->player2);

        $nextPlayer = $game->getNextPlayer();
        $game->play(3);  // El jugador fa un moviment a la columna 3

        // Comprova que el torn ha canviat al següent jugador
        $this->assertNotEquals($nextPlayer, $game->getNextPlayer());
    }

    /**
     * Prova que es llanci una excepció si es fa un moviment no vàlid.
     */
    public function testInvalidMove()
    {
        $this->expectException(IllegalMoveException::class);

        $game = new Game($this->player1, $this->player2);

        // Omple la columna 1 per simular un moviment no vàlid
        for ($i = 0; $i < 6; $i++) {
            $game->play(1);
        }

        // Ara la columna 1 està plena, i el proper moviment a aquesta columna ha de generar una excepció
        $game->play(1);
    }

    /**
     * Prova que el joc detecti correctament un guanyador després de 4 moviments consecutius.
     */
    public function testCheckWin()
    {
        $game = new Game($this->player1, $this->player2);

        // El jugador 1 fa 4 moviments consecutius horitzontals a la fila més baixa
        $game->play(1);
        $game->play(2); // Torn del jugador 2
        $game->play(2);
        $game->play(3); // Torn del jugador 2
        $game->play(3);
        $game->play(4); // Torn del jugador 2
        $game->play(3);
        $game->play(5); // Torn del jugador 2
        $game->play(4);

        // Comprova que el jugador 1 ha guanyat
        $this->assertEquals($this->player1, $game->getWinner());
    }

    /**
     * Prova que el moviment automàtic faci el moviment correcte.
     */
    public function testAutomaticMove()
    {
        $game = new Game($this->player1, $this->player2);

        // Simula que és el torn del jugador 2, que és automàtic
        $game->setNextPlayer(2);

        // El jugador automàtic fa un moviment
        $game->playAutomatic();

        // Comprova que el moviment s'ha fet correctament
        $this->assertNotNull($game->getBoard()->getSlots());
        $this->assertNull($game->getWinner()); // Encara no hi ha guanyador
    }

    /**
     * Prova la funció de reinici del joc.
     */
    public function testGameReset()
    {
        $game = new Game($this->player1, $this->player2);

        // El jugador 1 fa un moviment
        $game->play(1);

        // Reinicia el joc
        $game->reset();

        // Comprova que el tauler s'ha reiniciat
        $this->assertNull($game->getWinner());
        $this->assertInstanceOf(Board::class, $game->getBoard());
        $this->assertContains($game->getNextPlayer(), [1, 2]);
    }

    /**
     * Prova la funció de guardar i restaurar el joc.
     */
    public function testSaveAndRestoreGame()
    {
        $game = new Game($this->player1, $this->player2);

        // Fes un moviment i guarda l'estat del joc
        $game->play(1);
        $game->save();

        // Restaura el joc
        $restoredGame = Game::restore();

        // Comprova que el joc s'ha restaurat correctament
        $this->assertEquals($game->getBoard()->getSlots(), $restoredGame->getBoard()->getSlots());
        $this->assertEquals($game->getNextPlayer(), $restoredGame->getNextPlayer());
    }
}
