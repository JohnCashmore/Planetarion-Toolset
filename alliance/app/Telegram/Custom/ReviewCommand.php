<?php
namespace App\Telegram\Custom;

use App\Telegram\Custom\BaseCommand;
use App\User;
use App;
use DB;
use App\Scan;
use App\AdvancedUnitScan;
use App\UnitScan;
use App\Tick;

class ReviewCommand extends BaseCommand
{
    protected $command = "!review";

    public function execute()
    {
        if(!$this->isChannelAllowed()) return "You can not use that command in this channel";

        $user = User::where('tg_username', $this->userId)->first();  
        if($user->name <> "ChronoX") { return "This is still being written. ChronoX only."; }

		$user = User::where('name', $this->text)->first();
		if(!$user) { return "Who?"; }

		$members = User::orderBy('name', 'ASC')->where(['is_enabled' => 1])->whereNotNull('planet_id')->where('name', $user->name)->get();
		
		if(count($members)) {
			$response = "";
			foreach($members as $member) {
				if($member->planet) {
            				$scan = Scan::with('au')->where('id', $member->planet->latest_au)->first();
            				$scantype = "A";
            				if(!$scan) { 
                				$scan = Scan::with('u')->where('id', $member->planet->latest_u)->first();
                				$scantype = "U";
                				if(!$scan) { 
                    					$response .= $member->name . "(#) - There's no unit or advanced unit scan\n"; 
							continue;
                				}
                				else {
                    					$results = $scan->u;
                				}
            				}
            				else {
                				$results = $scan->au;
            				}

        				$currentTick = Tick::orderBy('tick', 'DESC')->first();
					$tick = $currentTick->tick;

					$scan_tick = $scan->tick;
					$scan_age = $tick - $scan_tick;

					$defence_ship = array("Spider", "Locust", "Black Widow", "Phantom", "Banshee", "Revenant", "Reaper", "Corsair", "Interceptor", "Ravager", "Clipper", "Vindicator", "Recluse");
					$defence_value = 0;
					$defence_ships = "";
					$attack_value = 0;
					$attack_ships = "";

					ForEach($results as $ship) {
						$ship_id = $ship->ship_id;
						$ship_amount = $ship->amount;

						if($scantype == "A") {
							$ship_query = AdvancedUnitScan::with('ship')->where('ship_id', $ship_id)->first();
						}
						else {
							$ship_query = UnitScan::with('ship')->where('ship_id', $ship_id)->first();
						}
						
						$ship_name = $ship_query->ship->name;
						$ship_metal = $ship_query->ship->metal;
						$ship_crystal = $ship_query->ship->crystal;
						$ship_eonium = $ship_query->ship->eonium;

						if(in_array($ship_name, $defence_ship)) {
							$defence_value = $defence_value + ((($ship_metal + $ship_crystal + $ship_eonium) * $ship_amount) / 100);
							$defence_ships .= $ship_name . ", ";
						}
						else {
							$attack_value = $attack_value + ((($ship_metal + $ship_crystal + $ship_eonium) * $ship_amount) / 100);
							$attack_ships .= $ship_name . ", ";
						}
					}

					$response .= "<strong>" . $member->name . " (Age:" . $scan_age . ") - Attack Value: " . number_shorten($attack_value, 1) . " - Defence Value: " . number_shorten($defence_value, 1) . "</strong>\n";
					$response .= "Defence Ships Found: " . substr($defence_ships, 0, -2) . "\nAttack Ships Found: " . substr($attack_ships, 0, -2);
				}
			}
			return $response;
		}
		return "There are no members";
    }
}