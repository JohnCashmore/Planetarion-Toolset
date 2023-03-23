<?php
namespace App\Telegram\Custom\Scans;

use App\Telegram\Custom\BaseCommand;
use App\User;
use App\DevelopmentScan;
use App\Planet;
use App;

class DevelopmentCommand extends BaseCommand
{
    protected $command = "!dscan";

    public function execute()
    {
	if(!$this->isWebUser()) return "User can not be authenticated with webby.";

        preg_match("/^(\d+)[.: ](\d+)[.: ](\d+)[ ]?(l)?$/", $this->text, $coords);

        $psearch = ($coords) ? $coords : false;

	if(!$psearch) { return "Usage: !dscan <x:y:z> [l]"; }

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

	$dscan = DevelopmentScan::with('scan')->where('id', $coords->latest_d)->first();

	if(!$dscan) { return "There's no development scan for this planet.\nBetter request one and add it to the webby."; }

	if($l == 1) { return "<a href='" . $dscan->scan->link . "'>Development Scan on " . $coords->x . ":" . $coords->y . ":" . $coords->z . " in tick " . $dscan->scan->tick . "</a>"; }

	$lightfactory = $dscan->light_factory;
	$mediumfactory = $dscan->medium_factory;
	$heavyfactory = $dscan->heavy_factory;
	$hulls = $dscan->hulls;
	$mining = $dscan->mining;
	$population = $dscan->population;

	$scan_types  = array(0 => "P", 1 => "L", 2 => "D", 3 => "U", 4 => "N", 5 => "I", 6 => "J", 7 => "A", 8 => "M");
	$scan_type = $scan_types[$dscan->waves];

	return "Development Scan on " . $coords->x . ":" . $coords->y . ":" . $coords->z . " in tick " . $dscan->scan->tick . "\n\nSurface Structures\nLight Factory: " . $lightfactory . "\nMedium Factory: " . $mediumfactory . "\nHigh Factory: " . $heavyfactory . "\nWave Amplifier: " . $dscan->wave_amplifier . "\nWave Distorter: " . $dscan->wave_distorter . "\nMetal Refinery: " . $dscan->metal_refinery . "\nCrystal Refinery: " . $dscan->crystal_refinery . "\nEonium Refinery: " . $dscan->eonium_refinery . "\nResearch Lab: " . $dscan->research_lab . "\nFinance Centre: " . $dscan->finance_centre . "\nSecurity Centre: " . $dscan->security_centre . "\nMilitary Centre: " . $dscan->military_centre . "\nStructure Defence: " . $dscan->structure_defence . "\n\nTechnology Levels\nSpace Travel: " . $dscan->travel . "\nInfrastructure: " . $dscan->infrastructure . "\nHulls: " . $hulls . "\nWaves: " . $dscan->waves . " (" . $scan_type . ")\nCore Extraction: " . $dscan->core . "\nCovert Ops: " . $dscan->covert_op . "\nAsteroid Mining: " . $mining . "\nPopulation Management: " . $population;
    }
}