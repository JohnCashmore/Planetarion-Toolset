<?php
namespace App\Telegram\Custom\Scans;

use App\Telegram\Custom\BaseCommand;
use App\User;
use App\AdvancedUnitScan;
use App\Planet;
use App\Scan;
use App;

class AdvancedUnitCommand extends BaseCommand
{
    protected $command = "!ascan";

    public function execute()
    {
	if(!$this->isWebUser()) return "User can not be authenticated with webby.";

        preg_match("/^(\d+)[.: ](\d+)[.: ](\d+)[ ]?(l)?$/", $this->text, $coords);

        $psearch = ($coords) ? $coords : false;

        if(!$psearch) { return "Usage: !ascan <x:y:z> [l]"; }

            if($psearch) {
                $x = $psearch[1];
                $y = $psearch[2];
                $z = $psearch[3];
            $l = isset($psearch[4]);

                $coords = Planet::where([
                'x' => $x,
                'y' => $y,
                'z' => $z
                ])->first();
        }

        if(!$coords) { return "No such planet"; }

        $ascan = Scan::with('au')->where('id', $coords->latest_au)->first();
        
        if(!$ascan) { return "There's no advanced unit scan for this planet.\nBetter request one and add it to the webby."; }

        if($l == 1) { return "<a href='" . $ascan->link . "'>Advanced Unit Scan on " . $coords->x . ":" . $coords->y . ":" . $coords->z . " in tick " . $ascan->tick . "</a>"; }

        $ships = $ascan->au;
        $AUShips = "";
        ForEach($ships as $ship) {
            $ship_id = $ship->ship_id;
            $ship_amount = $ship->amount;
            $ship_query = AdvancedUnitScan::with('ship')->where('ship_id', $ship_id)->first();
            $ship_name = $ship_query->ship->name;
            $AUShips .= sprintf("%s %s\n", $ship_name, $ship_amount);
        }

        return "Advanced Unit Scan on " . $coords->x . ":" . $coords->y . ":" . $coords->z . " in tick " . $ascan->tick . "\n\n" . $AUShips;
    }
}