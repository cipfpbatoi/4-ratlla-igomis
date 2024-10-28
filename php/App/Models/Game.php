<?php

namespace Joc4enRatlla\Models;

use Joc4enRatlla\Exceptions\IllegalMoveException;
use Joc4enRatlla\Models\Board;
use Joc4enRatlla\Models\Player;
use Joc4enRatlla\Services\Connect;

class Game
{
    private Board $board;
    private int $nextPlayer;
    
    private array $players;

    private ?Player $winner;
    
    private array $scores = [1 => 0, 2 => 0];
    public function __construct( Player $jugador1, Player $jugador2)
    {
         $this->board = new Board();
         $this->nextPlayer = random_int(1, 2);
         $this->players = [1 => $jugador1,2 => $jugador2];
         $this->winner = null;
    }
    
    

    public function getBoard(): Board
    {
        return $this->board;
    }
    
    public function getNextPlayer(): int
    {
        return $this->nextPlayer;
    }

    public function getPlayer( ): Player
    {
        return $this->players[$this->nextPlayer];
    }

    public function getWinner(): ?Player
    {
        return $this->winner;
    }
    
    public function setNextPlayer(int $jugador): void
    {
        $this->nextPlayer = $jugador;
    }
    
    public function getPlayers(): array
    {
        return $this->players;
    }
    
    public function getScores(): array
    {
        return $this->scores;
    }
    
    public function setScores(array $scores): void
    {
        $this->scores = $scores;
    }
    
    public function reset(): void
    {
        $this->board = new Board();
        $this->nextPlayer = random_int(1, 2);
        $this->winner = null;
    }

    public function play($columna){
        if (!$this->board->isValidMove($columna)){
            throw new IllegalMoveException("Moviment no vàlid");
        }
        $coord = $this->board->setMovementOnBoard($columna,$this->nextPlayer);

         if ($this->board->checkWin($coord)){
            $this->winner = $this->players[$this->nextPlayer];
            $this->scores[$this->nextPlayer]++;
        } else {
             $this->nextPlayer = ($this->nextPlayer == 1) ? 2 : 1;
        }
        $this->save();
    }

    public function playAutomatic(){
        $opponent = $this->nextPlayer === 1 ? 2 : 1;

        for ($col = 1; $col <= Board::COLUMNS; $col++) {
            if ($this->board->isValidMove($col)) {
                $tempBoard = clone($this->board);
                $coord = $tempBoard->setMovementOnBoard($col, $this->nextPlayer);

                if ($tempBoard->checkWin($coord)) {
                    $this->play($col);
                    return;
                }
            }
        }

        for ($col = 1; $col <= Board::COLUMNS; $col++) {
            if ($this->board->isValidMove($col)) {
                $tempBoard = clone($this->board);
                $coord = $tempBoard->setMovementOnBoard($col, $opponent);
                if ($tempBoard->checkWin($coord )) {
                    $this->play($col);
                    return;
                }
            }
        }

        $possibles = array();
        for ($col = 1; $col <= Board::COLUMNS; $col++) {
            if ($this->board->isValidMove($col)) {
                $possibles[] = $col;
            }
        }
        if (count($possibles) ) {
            $random = count($possibles) > 2 ? rand(-1, 1) : 0;
            $middle = (int) ((count($possibles) + 0.9) / 2) + $random;
            $inthemiddle = $possibles[$middle];
            $this->play($inthemiddle);
        }
    }



    public function save() {
        $_SESSION['game'] = serialize($this);
    }

    public static function restore(){
        return unserialize($_SESSION['game'],[Game::class]);
    }

    public function saveGame($db )   {

        // Serialitzar el board
        $usuari_id = $_SESSION['user_id'];
        $game  = $_SESSION['game'];


        // Inserir la nova partida
        $query = "INSERT INTO partides (usuari_id, game ) 
                  VALUES (:usuari_id, :game   ) 
                  ON DUPLICATE KEY UPDATE 
                  game = :game   ";

        $stmt = $db->prepare($query);
        $stmt->bindParam(':usuari_id', $usuari_id);
        $stmt->bindParam(':game', $game);

        return $stmt->execute();
    }

    // Recupera una partida des de la base de dades
    public static function restoreGame ($db ) {

        $usuari_id = $_SESSION['user_id'];
        $query = "SELECT * FROM partides WHERE usuari_id = :usuari_id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':usuari_id', $usuari_id);
        $stmt->execute();
        $partida = $stmt->fetch(\PDO::FETCH_ASSOC);

        return unserialize($partida['game'],[Game::class]);

    }





}



