<?php
namespace App\Telegram\Custom\Attacks;

use App\Telegram\Custom\BaseCommand;
use App\User;
use App\Planet;
use App\AdvancedUnitScan;
use App\UnitScan;
use App\Tick;
use App\Scan;
use App;
use DB;

class BattleCalcCommand extends BaseCommand
{
    protected $command = "!bcalc";

    public function execute()
    {
        if(!$this->text) { 
            return "Usage: !bcalc <class> <x:y:z> [x:y:z] [x:y:z] [x:y:z] - Make sure you use :'s with the coords."; 
        }

        preg_match("/(\S+)\s+(.*)/", $this->text, $params);

        $classes = ['FI', 'CO', 'DE', 'FR', 'BS', 'CR'];
        $shipclass = strtoupper($params[1]);

        if(!in_array($shipclass, $classes)) {
            return "This is not a valid class: " . $shipclass;
        }

        $currentTick = Tick::orderBy('tick', 'DESC')->first();
        $tick = $currentTick->tick;
        
        $coords = explode(" ", $params[2]);

        $totalfleets = 0;
        $bcalc = "";
        $summary = "";
        ForEach($coords as $coord) {
            preg_match("/^(\d+)[.:](\d+)[.:](\d+)$/", $coord, $target);
            $psearch = ($target) ? $target : false;

            if(!$psearch) { 
                return "Usage: !bcalc <class> <x:y:z> [x:y:z] [x:y:z] [x:y:z] - Make sure you use :'s with the coords."; 
            }

            if($psearch) {
                $x = $psearch[1];
                $y = $psearch[2];
                $z = $psearch[3];
            
                $planet = Planet::where([
                'x' => $x,
                'y' => $y,
                'z' => $z
                ])->first();
            }

            if(!$planet) { 
                return "Unable to find a planet with those coords: " . $x . ":" . $y . ":" . $z; 
            }

            $scan = Scan::with('au')->where('id', $planet->latest_au)->first();
            $scantype = "A";
            if(!$scan) { 
                $scan = Scan::with('u')->where('id', $planet->latest_u)->first();
                $scantype = "U";
                if(!$scan) { 
                    return "There's no unit or advanced unit scan for " . $planet->x . ":" . $planet->y . ":" . $planet->z . ".\nBetter request one and add it to the webby."; 
                }
                else {
                    $ships = $scan->u;
                }
            }
            else {
                $ships = $scan->au;
            }

            $scanage = $tick - $scan->tick;
            $totalfleets = $totalfleets + 1;
            $validships = 0;
            ForEach($ships as $ship) {
                $classes_ = array('FI' => 'Fighter', 'CO' => 'Corvette', 'DE' => 'Destroyer', 'FR' => 'Frigate', 'CR' => 'Cruiser', 'BS' => 'Battleship');

                $ship_id = $ship->ship_id;
                $ship_amount = $ship->amount;

                if($scantype == "A") {
                    $ship_query = AdvancedUnitScan::with('ship')->where('ship_id', $ship_id)->first();
                }
                else {
                    $ship_query = UnitScan::with('ship')->where('ship_id', $ship_id)->first();
                }
                
                $ship_name = $ship_query->ship->name;
                $ship_class = $ship_query->ship->class;

                if($ship_class == $classes_[$shipclass]) {
                    $validships = $validships + 1;
                    $bcalc .= sprintf("att_%d_%d=%d&" , $totalfleets, ($ship_id - 1), $ship_amount);
                }
            }

            if($validships < 1) {
                $totalfleets = $totalfleets - 1;
                continue;
            }

            $bcalc .= sprintf("att_planet_value_%d=%d&", $totalfleets, $planet->value);
            $bcalc .= sprintf("att_planet_score_%d=%d&", $totalfleets, $planet->score);
            $bcalc .= sprintf("att_coords_x_%d=%d&", $totalfleets, $planet->x);
            $bcalc .= sprintf("att_coords_y_%d=%d&", $totalfleets, $planet->y);
            $bcalc .= sprintf("att_coords_z_%d=%d&", $totalfleets, $planet->z);
    
            $summary .= sprintf("%s:%s:%s (%s/%d) - ", $planet->x, $planet->y, $planet->z, $scantype, intval($scanage));
        }

        if($totalfleets > 1) {
            $bcalc .= "att_fleets=" . $totalfleets;
        }

        if($totalfleets == 0) {
            return "No fleets found with the requested class: " . $shipclass;
        }

        $url = 'http://game.planetarion.com/bcalc.pl?' . $bcalc;

        return "<strong>Created a bcalc for your lazy ass</strong> \n<strong>Class</strong>: " . $shipclass . " \n<strong>Coords</strong>: " . substr($summary, 0, -2) . " \n<strong>Link</strong>: <a href='" . $url . "'>BattleCalc</a>";
    }
}