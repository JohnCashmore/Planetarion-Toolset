<?php
namespace App\Telegram\Custom\Scans;

use App\Telegram\Custom\BaseCommand;
use App\Planet;
use DB;

class ScanHistoryCommand extends BaseCommand
{
    protected $command = "!scanhistory";

    public function execute()
    {
      preg_match("/^(\d+)[.: ](\d+)[.: ](\d+)[ ]?(.*)?$/", $this->text, $coords);

      $psearch = ($coords) ? $coords : false;

	if(!$psearch) { return "Usage: !scanhistory <x:y:z> [P/D/U/N/J/A/M]"; }

      if($psearch) {
            $x = $psearch[1];
            $y = $psearch[2];
            $z = $psearch[3];
	      $t = ($psearch[4]) ? $psearch[4] : null;

            $coords = Planet::where([
              'x' => $x,
              'y' => $y,
              'z' => $z
            ])->first();
	}

	if(!$coords) { return "No such planet"; }

	$planet_id = $coords->id;

	if(isset($t)) {
	    $types = array("p", "d", "u", "n", "j", "a", "m");
	    if(!in_array(strtolower($t), $types)) {
	        return "This is not a valid scan type, use one of these: P/D/U/N/J/A/M";
	    }

	    $scan_names = array("p" => "App\PlanetScan", "d" => "App\DevelopmentScan", "u" => "App\UnitScan", "n" => "App\NewsScan", "j" => "App\JgpScan", "a" => "App\AdvancedUnitScan", "m" => "App\MilitaryScan");
	    $scan_type = $scan_names[strtolower($t)];
	    $scans = DB::table('scans')->where('planet_id', $planet_id)->where('scan_type', $scan_type)->orderBy('tick', 'desc')->orderBy('time', 'desc')->take(5)->get();
	}
	else {
          $scans = DB::table('scans')->where('planet_id', $planet_id)->orderBy('tick', 'desc')->orderBy('time', 'desc')->take(5)->get();
	}

	$response = "";
	ForEach($scans as $scan) {
	    $scan_names = array("App\PlanetScan" => "P", "App\DevelopmentScan" => "D", "App\UnitScan" => "U", "App\NewsScan" =>  "N", "App\JgpScan" => "J", "App\AdvancedUnitScan" => "A", "App\MilitaryScan" => "M");
	    $scan_type = $scan_names[$scan->scan_type];
	    $response .= "<a href='https://game.planetarion.com/showscan.pl?scan_id=" . $scan->pa_id . "'>Type: " . $scan_type . " - Tick: " . $scan->tick . " (" . $scan->time . ")</a>\n";
	}

	return "Scan History for " . $coords->x . ":" . $coords->y . ":" . $coords->z . "\n" . $response;
    }
}