<?php
namespace App\Telegram\Custom\Core;

use App\Telegram\Custom\BaseCommand;
use App\User;
use App\Planet;
use App\Ship;
use App;
use Config;

class AffordCommand extends BaseCommand
{
    protected $command = "!afford";

    public function execute()
    {
        if(!$this->isWebUser()) return "User can not be authenticated with webby.";

        preg_match("/^(\d+)[.: ](\d+)[.: ](\d+)[ ]?(\w+)?$/", $this->text, $match);

        $psearch = ($match) ? $match : false;

        if(!$psearch) {
            return "Usage: !afford <x:y:z> <ship>";
        }

        if($psearch) {
            $x = $psearch[1];
            $y = $psearch[2];
            $z = $psearch[3];
            $s = $psearch[4];

            $planet = Planet::with('latestP', 'latestP.scan')->where([
              'x' => $x,
              'y' => $y,
              'z' => $z
            ])->first();
        }

        if(!$planet) return sprintf("No planet with coords %s:%s:%s found.", $x, $y, $z);

        $ship = Ship::where('name', 'like', '%' . $s . '%')->first();
        if(!$ship) {
            return "No such ship: " . $s;
        }

        if(empty($planet->latestP)) return sprintf('No planet scan available on %d:%d:%d.', $x, $y, $z);

        $scan = $planet->latestP;
        $tick = $scan->scan->tick;
        $metal = intval(str_replace(',', '', $scan->res_metal));
        $crystal = intval(str_replace(',', '', $scan->res_crystal));
        $eonium = intval(str_replace(',', '', $scan->res_eonium));
        $prodRes = intval(str_replace(',', '', $scan->prod_res));
        $scanId = $scan->scan->pa_id;

        $costMetal = $ship->metal;
        $costCrystal = $ship->crystal;
        $costEonium = $ship->eonium;
        $totalCost = $ship->total_cost;

        $class_factory_table = [
            'Fighter' => 'factory_usage_light',
            'Corvette' => 'factory_usage_light',
            'Frigate' => 'factory_usage_medium',
            'Destroyer' => 'factory_usage_medium',
            'Cruiser' => 'factory_usage_heavy',
            'Battleship' => 'factory_usage_heavy'
        ];

        $prod_modifier_table = [
            'N' => 0.0,
            'L' => 0.33,
            'M' => 0.66,
            'H' => 1.0
        ];

        $capped_number = min($metal/$costMetal, $crystal/$costCrystal, $eonium/$costEonium);
        $overflow = $metal+$crystal+$eonium-($capped_number*($costMetal+$costCrystal+$costEonium));
        $buildable = $capped_number + (($overflow*.95)/$totalCost);

        $reply = sprintf("Newest planet scan on %s:%s:%s (id: %s, pt: %s)", $x,$y,$z,$scanId,$tick);
        $reply .= sprintf(" can purchase around %s: %s", $ship->name, number_format((int) $buildable));

        $governments = Config::get('governments');

        foreach($governments as $name => $gov) {
            $bonus = $gov['prod_cost'];
            if($bonus == 0) continue;
            $reply .= sprintf(" | %s: %s", $name, number_format(intval($buildable/(1+$bonus))));
        }

        $factoryUsage = $scan->{$class_factory_table[$ship->class]};
        if($prodRes > 0 and $factoryUsage != "N") {
            $max_prod_modifier=$prod_modifier_table[$factoryUsage];
            $buildable_from_prod = $buildable + $max_prod_modifier*$prodRes/$totalCost;
            $reply .= sprintf("\nCounting %s res in prod:", number_format($prodRes));
            $reply .= sprintf(" %s", number_format(intval($buildable_from_prod)));


            foreach($governments as $name => $gov) {
                $bonus = $gov['prod_cost'];
                if($bonus == 0) continue;
                $reply .= sprintf(" | %s: %s", $name, number_format(intval($buildable_from_prod/(1+$bonus))));
            }
        }

        return $reply;
    }
}
