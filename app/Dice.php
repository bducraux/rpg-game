<?php

/**
 * rpg-game - Dice.php
 * Author: bruno.ducraux
 * Date: 6/25/2017
 *
 * Description:
 * Dice class of the game, it will be responsible for the roll calculation of the dice
 * Dice will be instantiate by the name examples:
 * 1d6 = dice quantity equal 1 and dice sides equal 6
 * 2d8 = dice quantity equal 2 and dice sides equal 8
 * and so on...
 * will follow this rule to extract values from the dice name:
 * [Quantity] d [Faces]
 */

namespace App;

class Dice
{
    private static $quantity;
    private static $sides;

    /**
     * @param $_name -> Dice that will rolled like: 1d6, 2d6, 1d8, 1d20 ...
     * @return int
     */
    public static function rollDice($_name) {

        if ( !self::setDiceVarsByName($_name) ) {
            return false;
        }

        $result = 0;
        for($i=1; $i<=self::$quantity; $i++)
        {
            $roll = rand(1, self::$sides);

            $result = $result + $roll;
        }

        return $result;

    }

    /**
     * This function will set the dice vars, quantity and faces using the dice name.
     *
     * Minimal quantity equal 1 -> You can roll 0 dice
     * Minimal faces    equal 3 -> There is no dice with less than 3 faces
     *
     * This values will be set as default if one incorrect value is passed
     * So if $name = 0d2, 1d0, 1d1, 1d2 ... this will be set as 1d3
     *
     * @param $_name -> Dice that will rolled like: 1d6, 2d6, 1d8, 1d20 ...
     * @return bool
     *
     */
    private static function setDiceVarsByName($_name){
        $name = strtolower($_name);
        $diceArr = explode("d", $name);

        //Dice name not on the correct format like: [qtd]d[faces]
        if( count($diceArr) != 2 ) {
            return false;
        }

        //set Quantity
        $quantity = $diceArr[0];
        self::$quantity = $quantity < 1 ? 1 : $quantity;

        //set Faces
        $faces = $diceArr[1];
        self::$sides = $faces < 3 ? 3 : $faces;

        return true;
    }

}