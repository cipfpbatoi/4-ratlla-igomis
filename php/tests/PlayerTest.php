<?php

use Joc4enRatlla\Models\Player;
use PHPUnit\Framework\TestCase;

class PlayerTest extends TestCase
{
    /**
     * Prova el constructor i els getters de la classe Player.
     */
    public function testConstructorAndGetters()
    {
        $player = new Player('William', 'Vermell', true);

        $this->assertEquals('William', $player->getName());
        $this->assertEquals('Vermell', $player->getColor());
        $this->assertTrue($player->isAutomatic());
    }

    /**
     * Prova els setters de la classe Player.
     */
    public function testSetters()
    {
        $player = new Player('William', 'Vermell', true);

        // Prova setName
        $player->setName('Holosh');
        $this->assertEquals('Holosh', $player->getName());

        // Prova setColor
        $player->setColor('Blau');
        $this->assertEquals('Blau', $player->getColor());

        // Prova setAutomatic
        $player->setAutomatic(false);
        $this->assertFalse($player->isAutomatic());
    }

   
}

