<?php

use Joc4enRatlla\Controllers\GameController;
use Joc4enRatlla\Models\Game;
use Joc4enRatlla\Models\Player;
use Joc4enRatlla\Exceptions\IllegalMoveException;
use PHPUnit\Framework\TestCase;

class GameControllerTest extends TestCase
{
    protected function setUp(): void
    {
        // Inicialitza la sessió per a les proves
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    protected function tearDown(): void
    {
        // Neteja la sessió després de cada test
        $_SESSION = [];
        session_destroy();
    }

    /**
     * Prova que el controlador inicialitzi un nou joc quan no hi ha cap joc a la sessió.
     */
    public function testGameInitializationOnFirstRequest()
    {
        $request = ['name' => 'William', 'color' => 'Vermell'];

        // Mock de la funció loadView
        $this->mockLoadView();

        // Inicialitza el controlador
        $controller = new GameController($request);

        // Comprova que el joc ha estat creat i guardat a la sessió
        $this->assertArrayHasKey('game', $_SESSION);
        $this->assertInstanceOf(Game::class, Game::restore());

        // Comprova que el jugador 1 té el nom i el color correctes
        $game = Game::restore();
        $players = $game->getPlayers();
        $this->assertEquals('William', $players[1]->getName());
        $this->assertEquals('Vermell', $players[1]->getColor());
    }

    /**
     * Prova que el joc es restaura correctament si ja existeix a la sessió.
     */
    public function testGameRestorationFromSession()
    {
        // Inicialitza la sessió amb un joc
        $_SESSION['game'] = serialize(new Game(new Player('William', 'Vermell'), new Player('Jugador 2', 'Blau', true)));

        // Mock de la funció loadView
        $this->mockLoadView();

        // Crea el controlador, la sessió ja conté un joc
        $request = [];
        $controller = new GameController($request);

        // Comprova que el joc ha estat restaurat
        $this->assertArrayHasKey('game', $_SESSION);
        $this->assertInstanceOf(Game::class, Game::restore());

        // Comprova que el jugador 1 és "William"
        $game = Game::restore();
        $players = $game->getPlayers();
        $this->assertEquals('William', $players[1]->getName());
    }

    /**
     * Prova la funcionalitat de reiniciar el joc.
     */
    public function testGameReset()
    {
        // Inicialitza la sessió amb un joc
        $_SESSION['game'] = serialize(new Game(new Player('William', 'Vermell'), new Player('Jugador 2', 'Blau', true)));

        // Mock de la funció loadView
        $this->mockLoadView();

        // Crea el controlador amb una petició de reinici
        $request = ['reset' => true];
        $controller = new GameController($request);

        // Comprova que el joc ha estat reiniciat
        $this->assertArrayHasKey('game', $_SESSION);
        $game = Game::restore();
        $this->assertNull($game->getWinner());
        $this->assertInstanceOf(Game::class, $game);
    }

    /**
     * Prova que es llanci una excepció si es fa un moviment il·legal.
     */
    public function testIllegalMoveExceptionHandling()
    {
        // Inicialitza la sessió amb un joc
        $_SESSION['game'] = serialize(new Game(new Player('William', 'Vermell'), new Player('Jugador 2', 'Blau', true)));

        // Mock de la funció loadView
        $this->mockLoadView();

        // Crea el controlador amb una petició que fa un moviment il·legal
        $request = ['columna' => 1];

        // Omple la columna 1
        $game = Game::restore();
        for ($i = 0; $i < 6; $i++) {
            $game->play(1);
        }
        $game->save();

        $this->expectException(IllegalMoveException::class);
        
        // Crea el controlador amb la petició que fa un moviment il·legal
        $controller = new GameController($request);

        
    }

    /**
     * Mock per la funció loadView
     */
    private function mockLoadView()
    {
        // Aquest mètode fa un mock de la funció global loadView per evitar que faci cap sortida real
        if (!function_exists('loadView')) {
            function loadView($view, $params)
            {
                echo 'Vista carregada: ' . $view;
            }
        }
    }
}
