<?php
namespace App\Telegram\Custom\Scans;

use App\Telegram\Custom\BaseCommand;
use App\User;
use App\PlanetScan;
use App\Planet;
use App;
use App\Tick;

class PlanetCommand extends BaseCommand
{
    protected $command = "!pscan";

    public function execute()
    {
	if(!$this->isWebUser()) return "User can not be authenticated with webby.";

        preg_match("/^(\d+)[.: ](\d+)[.: ](\d+)[ ]?(l)?$/", $this->text, $coords);

        $psearch = ($coords) ? $coords : false;

	if(!$psearch) { return "Usage: !pscan <x:y:z> [l]"; }

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

	$pscan = PlanetScan::with('scan')->where('id', $coords->latest_p)->first();

	if(!$pscan) { return "There's no planet scan for this planet.\nBetter request one and add it to the webby."; }

        $currentTick = Tick::orderBy('tick', 'DESC')->first();
	$tick = $currentTick->tick;
	$age = $tick - $pscan->scan->tick;

	if($l == 1) { return "<a href='" . $pscan->scan->link . "'>Planet Scan on " . $coords->x . ":" . $coords->y . ":" . $coords->z . " in tick " . $pscan->scan->tick . " (Age:" . $age . ")</a>"; }

	return "Planet Scan on " . $coords->x . ":" . $coords->y . ":" . $coords->z . " in tick " . $pscan->scan->tick . " (Age:" . $age . ")\n\nCovOps\nAgents: " . $pscan->agents . "\nGuards: " . $pscan->guards ."\n\nRoids\nMetal: " . $pscan->roid_metal . "\nCrystal: " . $pscan->roid_crystal . "\nEonium: " . $pscan->roid_eonium . "\n\nResources\nMetal: " . $pscan->res_metal . "\nCrystal: " . $pscan->res_crystal . "\nEonium: " . $pscan->res_eonium . "\n\nFactory Usage\nLight: " . $pscan->factory_usage_light . "\nMedium: " . $pscan->factory_usage_medium . "\nHeavy: " . $pscan->factory_usage_heavy . "\nAmount: " . $pscan->prod_res;
    }
}