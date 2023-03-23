<?php
namespace App\Telegram\Custom\Attacks;

use App\Telegram\Custom\BaseCommand;
use App\Ship;
use Config;

class ProdticksCommand extends BaseCommand
{
    protected $command = "!prodticks";

    public function execute()
    {
        if(!$this->isWebUser()) return "User can not be authenticated with webby.";

        // !prodticks <amount> <shiptype> <cost_variance> <amount_of_factories> <gov> <pop_bonus>
        // !prodticks 10k chim -1.76 5 corp 60
        
        $string = explode(" ", $this->text);

        if (!$string[0] || !$string[1] || !isset($string[2]) || !$string[3] || !$string[4] || !isset($string[5])) return "usage: !prodticks <amount> <ship> <cost_variance> <amount_of_factories> <government> <pop_bonus>";

        $amount = short2num($string[0]);

        if($string[1]) {
            if(Ship::where('name', 'LIKE', '%' . $string[1] . '%')->count() == 1) {
                $ship = Ship::where('name', 'LIKE', '%' . $string[1] . '%')->first();
            } else {
                if(Ship::where('name', 'LIKE', '%' . $string[1] . '%')->count() == 0) {
                    return "Can't find a ship with that name";
                } else {
                    // Check for actual name
                    if($ship = Ship::where('name', $string[1])->count() == 1) {
                        $ship = Ship::where('name', $string[1])->first();
                    } 
                    // Too many matches
                    else {
                        $ships = Ship::where('name', 'LIKE', '%' . $string[1] . '%')->get()->pluck('name')->toArray();
                        return "Ship name is too ambiguous (" . implode(", ", $ships) . ")";
                    }
                }
            }
        }

        if(isset($ship)) {
            $metal = $ship->metal;
            $crystal = $ship->crystal;
            $eonium = $ship->eonium;
            $race = $ship->race;
        }
        
        // PU = (total_resources_spent^1/2)*LN(total_resources_spent^2) 

        // PU Output = int(((4000 * # factories)^0.98) * (1 + (pop_bonus + gov_bonus + race_bonus) / 100))

        // Production time = (Required Production Units + (10000*# factories))/Output, rounded up to the nearest number.
        // !prodticks 10k chim -1.76 5 corp 60

        $cost_variance = $string[2];

        $total_metal = $amount * intval($metal * ((100 + $cost_variance)/100));
        $total_crystal = $amount * intval($crystal * ((100 + $cost_variance)/100));
        $total_eonium = $amount * intval($eonium * ((100 + $cost_variance)/100));

        $total_resources = $total_metal + $total_crystal + $total_eonium;

        $factories = $string[3];
        $gov_name = strtolower($string[4]);

        $governments = Config::get('governments');
        $gov_bonus = "";

        foreach($governments as $name => $gov) {
            if (str_contains(strtolower($name), $gov_name)) {
                $gov_bonus = $gov['prod_time'] * 100;
            }
        }
        // modification of gov_bonus, I wonder if prod_time should be 0.2 instead of 1.2 for 'Socialism' for instance... - Sven
        if ($gov_bonus > 100) {
            $gov_bonus = $gov_bonus - 100;
        }

        $pop_bonus = $string[5];
        if ($pop_bonus > 60) {
            $pop_bonus = 60;
        }

        switch ($race) {

            case "Terran":
                $race_bonus = 10;
                break;

            case "Cathaar";
                $race_bonus = 0;
                break;

            case "Xandathrii":
                $race_bonus = 5;
                break;

            case "Zikonian":
                $race_bonus = 15;
                break;

            case "Eitraides":
                $race_bonus = 0;
                break;
        }
        
        $pu = ($total_resources ** 0.5) * log($total_resources ** 2);

        $pu_output = intval(((4000 * $factories) ** 0.98) * (1 + ($pop_bonus + $gov_bonus + $race_bonus) / 100));
        
        // Production time = (Required Production Units + (10000*# factories))/Output, rounded up to the nearest number.

        $prod_time = ($pu + (10000 * $factories)) / $pu_output;

        return "Producing " . $amount . " " . $ship->name . " using " . $factories . " factories with shipwrights on " . $pop_bonus . "%" . " takes: " . ceil($prod_time) . " ticks.";
    }
}