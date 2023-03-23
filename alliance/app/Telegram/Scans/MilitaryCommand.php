<?php
namespace App\Telegram\Custom\Scans;

use App\Telegram\Custom\BaseCommand;
use App\User;
use App\MilitaryScan;
use App\Planet;
use App\Scan;
use App;

class MilitaryCommand extends BaseCommand
{
    protected $command = "!mscan";

    public function execute()
    {
	if(!$this->isWebUser()) return "User can not be authenticated with webby.";

        preg_match("/^(\d+)[.: ](\d+)[.: ](\d+)[ ]?(base|f1|f2|f3)?(l)?$/", $this->text, $coords);

        $msearch = ($coords) ? $coords : false;

        if(!$msearch) { return "Usage: !mscan <x:y:z> [fleet] [l]"; }
        if($msearch) {
                $x = $msearch[1];
                $y = $msearch[2];
                $z = $msearch[3];
            	$fleet = isset($msearch[4]);
            	$l = isset($msearch[5]);


                $coords = Planet::where([
                'x' => $x,
                'y' => $y,
                'z' => $z
                ])->first();
        }

        if(!$coords) { return "No such planet"; }

        $mscan = Scan::with('m')->where('id', $coords->latest_m)->first();
        
        if(!$mscan) { return "There's no military scan for this planet.\nBetter request one and add it to the webby."; }

        if($l == 1) { return "<a href='" . $mscan->link . "'>Military Scan on " . $coords->x . ":" . $coords->y . ":" . $coords->z . " in tick " . $mscan->tick . "</a>"; }

        $ships = $mscan->m;
        $MShips = "";



        $sumBase = $sumFleet1 = $sumFleet2 = $sumFleet3 = 0;

        ForEach($ships as $ship) {
            $ship_id = $ship->ship_id;
            $shipsBase = $ship->base;
            $sumBase += $shipsBase;
            $shipsFleet1 = $ship->f1;
            $sumFleet1+= $shipsFleet1;
            $shipsFleet2 = $ship->f2;
            $sumFleet2+= $shipsFleet2;
            $shipsFleet3 = $ship->f3;
            $sumFleet3+= $shipsFleet3;

            $ship_query = MilitaryScan::with('ship')->where('ship_id', $ship_id)->first();
            $ship_name = $ship_query->ship->name;
            if ($fleet == 1) {
                switch ($msearch[4]) {
                    case 'base':
                        $MShips .= sprintf("%s %s\n", $ship_name, $shipsBase);
                        break;
                    case 'f1':
                        $MShips .= sprintf("%s %s\n", $ship_name, $shipsFleet1);
                        break;
                    case 'f2':
                        $MShips .= sprintf("%s %s\n", $ship_name, $shipsFleet2);
                        break;
                    case 'f3':
                        $MShips .= sprintf("%s %s\n", $ship_name, $shipsFleet3);
                        break;

                }
            }
        }

        if ($fleet == 1) {
            return "Military Scan on " . $coords->x . ":" . $coords->y . ":" . $coords->z . " in tick " . $mscan->tick . " showing fleet " . $msearch[4] . "\n\n" . $MShips;
        } else {
            $MShips = sprintf("base: %s fleet1: %s fleet2: %s fleet3: %s\n", $sumBase, $sumFleet1, $sumFleet2, $sumFleet3);
            return "Military Scan on " . $coords->x . ":" . $coords->y . ":" . $coords->z . " in tick " . $mscan->tick . "\n\n" . $MShips;

           #        $MShips .= sprintf("%s %s\n", $sumBase, $sumBase);
    #        return "Military Scan on " . $coords->x . ":" . $coords->y . ":" . $coords->z . " in tick " . $mscan->tick . "\n\n" . $MShips;
        }

    }
}
