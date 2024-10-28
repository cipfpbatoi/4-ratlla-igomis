<?php

namespace Joc4enRatlla\Controllers;

use Joc4enRatlla\Models\Player;
use Joc4enRatlla\Models\Game;
use Joc4enRatlla\Exceptions\IllegalMoveException;


class GameController
{
    private Game $game;
    
    public function __construct($request=null)
    {
        if (!isset($_SESSION['game'])) {
            $jugador1 = new Player( $request['name'], $request['color']);
            $jugador2 = new Player( "Jugador 2", "pink", true);
            $this->game = new Game($jugador1, $jugador2);
            $this->game->save();
        } else {
            $this->game = Game::restore();
        }
        $this->play($request);


    }




    public function play(Array $request)
    {
        $error = "";
        if (isset($request['reset'])){
            $this->game->reset();
            $this->game->save();
        }
        if (isset($request['exit'])){
            unset($_SESSION['game']);
            session_destroy();
            header("location:/index.php");
            exit();
        }
        if (!$this->game->getWinner() && !$this->game->getPlayer()->isAutomatic() && isset($request['columna'])) {
            try {
                $this->game->play($request['columna']);
            } catch (IllegalMoveException $e) {
                $error = $e->getMessage();
            }
        }
        if (!$this->game->getWinner() && $this->game->getPlayer()->isAutomatic()){
             $this->game->playAutomatic();
        }
        $board = $this->game->getBoard();
        $players = $this->game->getPlayers();
        $winner = $this->game->getWinner();
        $scores = $this->game->getScores();

        loadView('index',compact('board','players','winner','scores','error'));
     }
}