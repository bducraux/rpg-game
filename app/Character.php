<?php

/**
 * rpg-game - Character Class
 * Author: bruno.ducraux
 * Date: 6/25/2017
 *
 * Description:
 * Class for the character of the game
 */

namespace App;

class Character
{
    private $name;
    private $attack;
    private $defense;
    private $strength;
    private $agility;
    private $damage;
    private $hp;

    /**
     * Character constructor.
     * @param $name
     * @param $attack
     * @param $defense
     * @param $strength
     * @param $agility
     * @param $damage
     * @param $hp
     */
    public function __construct($name, $attack, $defense, $strength, $agility, $damage, $hp)
    {
        $this->name = $name;
        $this->attack = $attack;
        $this->defense = $defense;
        $this->strength = $strength;
        $this->agility = $agility;
        $this->damage = $damage;
        $this->hp = $hp;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getAttack()
    {
        return $this->attack;
    }

    /**
     * @return string
     */
    public function getDefense()
    {
        return $this->defense;
    }

    /**
     * @return string
     */
    public function getStrength()
    {
        return $this->strength;
    }

    /**
     * @return string
     */
    public function getAgility()
    {
        return $this->agility;
    }

    /**
     * @return string
     */
    public function getDamage()
    {
        return $this->damage;
    }

    /**
     * @return string
     */
    public function getHp()
    {
        return $this->hp;
    }
}