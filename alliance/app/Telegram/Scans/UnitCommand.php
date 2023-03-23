<?php
namespace App\Telegram\Custom\Scans;

use App\Telegram\Custom\BaseCommand;
use App\User;
use App\UnitScan;
use App\Planet;
use App\Scan;
use App;

class UnitCommand extends BaseCommand
{
    protected $command = "!uscan";

    public function execute()
    {
	if(!$this->isWebUser()) return "User can not be authenticated with webby.";

        preg_match("/^(\d+)[.: ](\d+)[.: ](\d+)[ ]?(l)?$/", $this->text, $coords);

        $psearch = ($coords) ? $coords : false;

        if(!$psearch) { return "Usage: !uscan <x:y:z> [l]"; }

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

        $uscan = Scan::with('u')->where('id', $coords->latest_u)->first();
        
        if(!$uscan) { return "There's no advanced unit scan for this planet.\nBetter request one and add it to the webby."; }

        if($l == 1) { return "<a href='" . $uscan->link . "'>Unit Scan on " . $coords->x . ":" . $coords->y . ":" . $coords->z . " in tick " . $uscan->tick . "</a>"; }

        $ships = $uscan->u;
        $UShips = "";
        ForEach($ships as $ship) {
            $ship_id = $ship->ship_id;
            $ship_amount = $ship->amount;
            $ship_query = UnitScan::with('ship')->where('ship_id', $ship_id)->first();
            $ship_name = $ship_query->ship->name;
            $UShips .= sprintf("%s %s\n", $ship_name, $ship_amount);
        }

        return "Unit Scan on " . $coords->x . ":" . $coords->y . ":" . $coords->z . " in tick " . $uscan->tick . "\n\n" . $UShips;
    }
}