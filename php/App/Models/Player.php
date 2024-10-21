<?php

namespace Joc4enRatlla\Models;

/**
 * Classe Player
 *
 * Representa un jugador del joc 4 en ratlla
 */
class Player {
    // Propietats
    private $name;      // Nom del jugador
    private $color;     // Color de les fitxes
    private $isAutomatic; // Forma de jugar (automàtica/manual)




    /**
     * Constructor de la classe Player
     *
     * @param string $name Nom del jugador
     * @param string $color Color de les fitxes
     * @param bool $isAutomatic Indica si el jugador és automàtic o no
     */

    public function __construct( $name, $color, $isAutomatic = false) {

        $this->name = $name;
        $this->color = $color;
        $this->isAutomatic = $isAutomatic;
    }

    // Getters



    /**
     * Obté el nom del jugador
     *
     * @return string Nom del jugador
     */
    public function getName() {
        return $this->name;
    }
    /**
     * Obté el color de les fitxes del jugador
     *
     * @return string Color de les fitxes
     */
    public function getColor() {
        return $this->color;
    }

    /**
     * Indica si el jugador és automàtic o no
     *
     * @return bool Cert si el jugador és automàtic, fals si no ho és
     */
    public function isAutomatic() {
        return $this->isAutomatic;
    }

    // Setters
    /**
     * Estableix el nom del jugador
     *
     * @param string $name Nom del jugador
     * @return Player
     */
    public function setName($name) {
        $this->name = $name;
        return $this;
    }
    /**
     * Estableix el color de les fitxes del jugador
     *
     * @param string $color Color de les fitxes
     * @return Player
     */
    public function setColor($color) {
        $this->color = $color;
        return $this;
    }
    /**
     * Estableix si el jugador és automàtic o no
     *
     * @param bool $isAutomatic Cert si el jugador és automàtic, fals si no ho és
     * @return Player
     */
    public function setAutomatic($isAutomatic) {
        $this->isAutomatic = $isAutomatic;
        return $this;
    }

    /**
     * Retorna una representació en forma de cadena de l'objecte Player
     *
     * @return string Representació de l'objecte Player
     */
    public function __toString() {
        $playMode = $this->isAutomatic ? 'Automàtic' : 'Manual';
        $ret =  "Nom: " . $this->name . "<br/>";
        $ret .= "Color de les fitxes: " . $this->color . "<br/>";
        $ret .= "Forma de jugar: " . $playMode . "<br/>";
        return $ret;
    }
}



