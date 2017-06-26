<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Dice;
use App\Character;

class GameController extends Controller
{
    public function rollInitiative( Request $request ) {
        //Get data from the request
        $data = $request->json()->all();

        //Get characters array
        $chars = $this->getCharArrayFromRequestData($data);

        //Result array that will be sent as response
        $result = array();
        //Verbose result for the roll
        $verboseResult = "Initiative round started!\n";

        //Roll char1 initiative

        $roll = Dice::rollDice("1d20");
        $char1 = $this->getCharacterObjectFromArray($chars[0]);
        $char1_Name = $char1->getName();
        $char1_Agility = (int)$char1->getAgility();
        $char1_Initiative = $roll + $char1_Agility;

        //Set result for this char
        $result[$char1_Name] = array( "roll" => $roll, "bonus" => $char1_Agility, "initiative" => $char1_Initiative);
        $verboseResult .= "Rolling initiative for $char1_Name\n";
        $verboseResult .= "Dice roll = $roll + $char1_Agility (Agility bonus)\n";
        $verboseResult .= "Initiative = $char1_Initiative\n\n";

        //Roll char2 initiative
        $roll = Dice::rollDice("1d20");
        $char2 = $this->getCharacterObjectFromArray($chars[1]);
        $char2_Name = $char2->getName();
        $char2_Agility = (int)$char2->getAgility();
        $char2_Initiative = $roll + $char2_Agility;

        //Set result for this char
        $result[$char2_Name] = array( "roll" => $roll, "bonus" => $char2_Agility, "initiative" => $char2_Initiative);
        $verboseResult .= "Rolling initiative for $char2_Name\n";
        $verboseResult .= "Dice roll = $roll + $char2_Agility (Agility bonus)\n";
        $verboseResult .= "Initiative = $char2_Initiative\n\n";

        //Set Winner
        $result["Winner"] = $char1_Initiative > $char2_Initiative ? $char1->getName() : $char2->getName();

        $verboseResult .= $result["Winner"]. " won the initiative round, and will start attacking!\n\n";

        //Verbose result
        $result["VerboseResult"] = $verboseResult;

        return $this->success($result, 200);
    }

    public function rollAttack( Request $request)
    {
        //Get data from the request
        $data = $request->json()->all();

        //Get characters array

        //Get attacker
        if (!array_key_exists("attacker", $data)) {
            abort(400, "Missing attacker on request.");
        }
        $attacker = $data["attacker"];


        $chars = $this->getCharArrayFromRequestData($data);

        //set chars as attacker and defender
        if ($chars[0]["name"] == $attacker) {
            $attacker = $chars[0];
            $defender = $chars[1];
        } else {
            $attacker = $chars[1];
            $defender = $chars[0];
        }

        //Result array that will be sent as response
        $result = array();
        $verboseResult = "Start attack round! {$attacker["name"]} is attacking {$defender["name"]}!\n\n";

        $attack = 0;
        $defense = 0;
        //Continue rolling until is not draw
        while ($attack == $defense) {
            //Roll attack
            $atkRoll = Dice::rollDice("1d20");
            $atkBonus = (int)$attacker["agility"] + (int)$attacker["attack"];
            $attack = $atkRoll + $atkBonus;

            //Roll defense
            $defRoll = Dice::rollDice("1d20");
            $defBonus = (int)$defender["agility"] + (int)$defender["defense"];
            $defense = $defRoll + $defBonus;
        }


        //Attack Result
        $result["Attacker"] = array("name" => $attacker["name"], "roll" => $atkRoll, "bonus" => $atkBonus, "attack" => $attack);
        $verboseResult .= "Rolling dice for {$attacker["name"]} attack...\n";
        $verboseResult .= "Dice roll = $atkRoll + $atkBonus (Agility + Attack bonus)\n";
        $verboseResult .= "Attack = $attack\n\n";

        //Defense Result
        $result["Defender"] = array("name" => $defender["name"],"roll" => $defRoll, "bonus" => $defBonus, "defense" => $defense);
        $verboseResult .= "Rolling dice for {$defender["name"]} defense...\n";
        $verboseResult .= "Dice roll = $defRoll + $atkBonus (Agility + Defense bonus)\n";
        $verboseResult .= "Defense = $defense\n\n";

        //Check if attack hit
        if( $attack > $defense ) {
            //Hit!
            //roll for damage
            $damageRoll = Dice::rollDice($attacker["damage"]);
            $bonus = (int)$attacker["strength"];
            $damage = $damageRoll + $bonus;

            //apply damage
            $defender["hp"] = $defender["hp"] - $damage;

            $result["AttackResult"] = array("result" => "hit", "damageRoll" => $damageRoll, "bonus" => $bonus, "damage" => $damage);
            $verboseResult .= "{$attacker["name"]} attack hit the {$defender["name"]} for {$damage} of damage!\n";
        }
        else {
            $result["AttackResult"] = array("result" => "miss");
            $verboseResult .= "{$attacker["name"]} missed the attack on {$defender["name"]}!\n";
        }


        //Character after battle
        $result["characters"][] = $attacker;
        $result["characters"][] = $defender;

        //Verbose result
        $result["VerboseResult"] = $verboseResult;

        return $this->success($result, 200);
    }

    private function getCharArrayFromRequestData($data){
        //Check if character array key exists on the request data
        if( !array_key_exists("characters", $data) ){
            abort(400, "Bad request - Characters not found on request");
        }

        //Get characters array from the request data
        $chars = $data["characters"];
        $cnt_chars = count($chars);

        //Check if there is two characters to roll the initiative
        if ( $cnt_chars != 2 ) {
            abort(400, "Bad request - We need two characters to run initiative roll. Passed:".$cnt_chars);
        }

        return $chars;
    }

    private function getCharacterObjectFromArray($charArray) {

        //Validate character name
        if( !array_key_exists("name", $charArray) ){
            abort(400, "Bad request - Missing character name.");
        }

        //Validate character attack
        if( !array_key_exists("attack", $charArray) ){
            abort(400, "Bad request - Missing character attack .");
        }

        //Validate character defense
        if( !array_key_exists("defense", $charArray) ){
            abort(400, "Bad request - Missing character defense .");
        }

        //Validate character strength
        if( !array_key_exists("strength", $charArray) ){
            abort(400, "Bad request - Missing character strength.");
        }

        //Validate character agility
        if( !array_key_exists("agility", $charArray) ){
            abort(400, "Bad request - Missing character agility.");
        }

        //Validate character damage
        if( !array_key_exists("damage", $charArray) ){
            abort(400, "Bad request - Missing character damage.");
        }

        //Validate character hp
        if( !array_key_exists("hp", $charArray) ){
            abort(400, "Bad request - Missing character HP.");
        }

        return new Character($charArray["name"], $charArray["attack"], $charArray["defense"], $charArray["strength"], $charArray["agility"], $charArray["damage"], $charArray["hp"]);

    }
}
