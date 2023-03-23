<?php
namespace App\Telegram\Custom\Scans;

use App\Telegram\Custom\BaseCommand;
use App\User;
use App\JgpScan;
use App\Planet;
use App;
use App\Tick;

class JumpgateCommand extends BaseCommand
{
    protected $command = "!jscan";

    public function execute()
    {
	if(!$this->isWebUser()) return "User can not be authenticated with webby.";

        preg_match("/^(\d+)[.: ](\d+)[.: ](\d+)[ ]?(l)?$/", $this->text, $coords);

        $psearch = ($coords) ? $coords : false;

        if(!$psearch) { return "Usage: !jscan <x:y:z> [0]"; }

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

        $jscan = Jgpscan::with('scan')->where('id', $coords->latest_j)->first();

        if(!$jscan) { return "There's no jumpgate scan for this planet.\nBetter request one and add it to the webby."; }

        $currentTick = Tick::orderBy('tick', 'DESC')->first();
	$tick = $currentTick->tick;
	$age = $tick - $jscan->scan->tick;
        
	if($l == 1) { return "<a href='" . $jscan->scan->link . "'>Jumpgate Scan on " . $coords->x . ":" . $coords->y . ":" . $coords->z . " in tick " . $jscan->scan->tick . " (Age:" . $age . ")</a>"; }

        $response = "<a href='" . $jscan->scan->link . "'>Jumpgate Scan on " . $coords->x . ":" . $coords->y . ":" . $coords->z . " in tick " . $jscan->scan->tick . " (Age:" . $age . ")</a>\n\n";

        try {
            $html = file_get_contents($jscan->scan->link);
        } catch(\Exception $e) {
            $response .= "Couldn't read url!";
	    return $response;
        }

	$lines = explode("\n", $html);

	for ($i=1; $i < 15; $i++) {
            ${"eta".$i."_Defend_Fleets"} = 0;
            ${"eta".$i."_Defend_Ships"} = 0;
            ${"eta".$i."_Attack_Fleets"} = 0;
            ${"eta".$i."_Attack_Ships"} = 0;
            ${"eta".$i."_Return_Fleets"} = 0;
            ${"eta".$i."_Return_Ships"} = 0;
	}

	ForEach($lines as $line) {
	    if(str_contains($line, 'Scan time')) {
                preg_match('/>Scan time\: .* (\d+\:\d+\:\d+)/', $line, $time);
	        $time = $time[1];
	    }

	    if(str_contains($line, 'Jumpgate Probe on')) {
                preg_match('/>([^>]+) on (\d+)\:(\d+)\:(\d+) in tick (\d+)/', $line, $tick);
	        $scan_x = $tick[2];
	        $scan_y = $tick[3];
	        $scan_z = $tick[4];
	        $tick = $tick[5];
            }

	    preg_match('/<tr[^>]*><td[^>]*><a[^>]*>(\d+):(\d+):(\d+)<\/a> \(<span[^>]*>(\w+)<\/span>\)<\/td><td[^>]*>(\w+)<\/td><td[^>]*>(.+)<\/td><td[^>]*>(\d+)<\/td><td[^>]*>(\d+)<\/td><\/tr>/', $line, $fleets);
	    if($fleets) {
	        $x = $fleets[1];
	        $y = $fleets[2];
	        $z = $fleets[3];
	        $eta = $fleets[7];
	        $missionType = $fleets[5];
	        $fleetName = $fleets[6];
	        $shipCount = $fleets[8];

		${"eta".$eta."_".$missionType."_Fleets"} = ${"eta".$eta."_".$missionType."_Fleets"} + 1;
		${"eta".$eta."_".$missionType."_Ships"} = ${"eta".$eta."_".$missionType."_Ships"} + $shipCount;
	    }
	}

	for ($i=1; $i < 15; $i++) {
	    if(${"eta".$i."_Return_Fleets"} > 0 || ${"eta".$i."_Attack_Fleets"} > 0 || ${"eta".$i."_Defend_Fleets"} > 0) {
	        $response .= "ETA: ".$i." - A: " . number_format(${"eta".$i."_Attack_Ships"}) . "(" . ${"eta".$i."_Attack_Fleets"} . ") / D: " . number_format(${"eta".$i."_Defend_Ships"}) . "(". ${"eta".$i."_Defend_Fleets"} . ") - R: " . number_format(${"eta".$i."_Return_Ships"}) . "(" . ${"eta".$i."_Return_Fleets"} . ")\n";
	    }
	}

	return $response;
    }
}