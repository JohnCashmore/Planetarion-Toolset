<?php
namespace App\Telegram\Custom;

use App\Telegram\Custom\BaseCommand;
use App\Telegram\Custom\JpgCommand;
use App\PlanetHistory;
use App\Scan;
use App\ScanQueue;
use App\PlanetScan;
use App\DevelopmentScan;
use App\UnitScan;
use App\AdvancedUnitScan;
use App\NewsScan;
use App\JgpScan;
use App\Ship;
use App\Planet;
use App\Tick;
use App\User;
use App\FleetMovement;
use App\IntelChange;
use App\Services\Scans\PlanetScanParser;
use App\Services\Scans\DevelopmentScanParser;
use App\Services\Scans\UnitScanParser;
use App\Services\Scans\NewsScanParser;
use App\Services\Scans\JumpgateScanParser;
use App\Services\Scans\AdvancedUnitScanParser;
use Config;

use Artisan;


use DB;


class JpgCommand extends BaseCommand
{
	protected $command = "!jpg";

	public function execute()
	{
		// validation
		 if(!isset($this->text)):
			 return 'usage: !jpg <scan link>';
		 endif;
		 
		 $allScans = explode(PHP_EOL, $this->text);

		 $parsed = [];

		 $tick = Tick::orderBy('tick', 'DESC')->first();

		 $this->tick = $tick;

		 $ps = Planet::all();

		 $planets = [];

		 foreach($ps as $planet) {
			 $planets[$planet->x . ":" . $planet->y . ":" . $planet->z] = $planet;
		 }
		 
		 $incs = 0;
		 foreach($allScans as $url) {
			 preg_match('!https://[^/]+/(?:showscan|waves).pl\?scan_id=([0-9a-zA-Z]+)!', $url, $single);
			 preg_match('!https://[^/]+/(?:showscan|waves).pl\?scan_grp=([0-9a-zA-Z]+)!', $url, $group);

			 try {
				 $html = file_get_contents($url);
			 } catch(\Exception $e) {
				 return response()->json(['error' => 'Couldn\'t read url'], 422);
			 }

			 $scans = explode("<hr>", $html);
			 $count = 0;

			 foreach($scans as $scan) {

				 preg_match("/scan_id=([0-9a-zA-Z]+)/", $scan, $id);
				 
				 if(isset($id[1])) {
					 $scanId = $id[1];

					 preg_match('/>Scan time\: .* (\d+\:\d+\:\d+)/', $scan, $time);
					 preg_match('/>([^>]+) on (\d+)\:(\d+)\:(\d+) in tick (\d+)/', $scan, $tick);

					 if(!$tick) continue;

					 $scanType = strtoupper($tick[1]);
					 $x = $tick[2];
					 $y = $tick[3];
					 $z = $tick[4];
					 $tick = $tick[5];
					 $time = $time[1];

					 // Planet gone
					 if(!isset($planets[$x . ":" . $y . ":" . $z])) continue;
					 $planet = $planets[$x . ":" . $y . ":" . $z];
					 $planetId = ($planet) ? $planet->id : null;

					 if($planetId) {
						 $scan = preg_replace("/\s+/", "", $scan);

						 if($scanType == 'JUMPGATE PROBE') {
							 
							 $jumpgateScan = new JumpgateScanParser();
							 $jumpgateScan->setScan($scan)
							   ->setScanId($scanId)
							   ->setPlanetId($planetId)
							   ->setTick($tick)
							   ->setTime($time)
							   ->execute();
							 
							 // loop scan
							 $fleetsString = '';
							 if (!isset($attackers))
								 $attackers = array();
							 foreach ($jumpgateScan->movement as $fleet):
								 $fleetPlanet = Planet::find($fleet['planet_from_id']);
								 switch ($fleet['mission_type']):
									 case 'Attack':
									 	$fleetsString .= sprintf('Hostile fleet "%s" showing on JPG from %s:%s:%s', $fleet['fleet_name'], $fleetPlanet['x'], $fleetPlanet['y'], $fleetPlanet['z']) . PHP_EOL;
										$attackers[$fleetPlanet['x']  . ':' . $fleetPlanet['y'] . ':' . $fleetPlanet['z']] = true;
										
										break;
								endswitch;
							 endforeach;
							 
							 // send message
				 			$user = User::getUserByPlanetId($planet->id);
				 			if (is_array($user) && isset($fleetsString) && strlen($fleetsString) > 0):
				 				$transit = new \App\Services\Transit();
				 				$transit->sms($user['name'], $fleetsString);
				 				$incs++;
				 			 endif;
						 }
					 }
				 }
			}
			
		 }
		 // parse list of attacking planets
		 $attackingPlanets = '';
		 foreach ($attackers as $coords => $value):
			 $attackingPlanets .= $coords . ' ';
		 endforeach;
		 	 
		 return sprintf('Sent %s messages regarding incs from: %s', $incs, $attackingPlanets);

	}
}