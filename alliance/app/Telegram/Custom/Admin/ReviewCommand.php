<?php
namespace App\Telegram\Custom\Admin;

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

        //if($user->role_id <> 1) {
        //    return "You're not the boss of me....";
        //}

		$member = ltrim($this->text, '@');
		$tgdb = DB::table('user')->where('username', $member)->first();
		if(!isset($tgdb)) {
			$user = User::where('name', 'like', '%' . $member . '%')->first();
			if(!isset($user->name)) {
				$tgdb = DB::table('user')->where('first_name', 'like', '%' . $member . '%')->first();
				if(!isset($tgdb)) {
					return "No user found with this @telegram_username, webby_username or Telegram name: " . $member;
				}
				else {
					$user = User::where('tg_username', $tgdb->id)->first();
				}
			}
		}
		else {
			$user = User::where('tg_username', $tgdb->id)->first();
		}

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

					$race_short = $member->planet->race;
					$races = array("Ter" => "Terran", "Cat" => "Cathaar", "Xan" => "Xandathrii", "Zik" => "Zikonian", "Etd" => "Eitraides");
					$race = $races[$race_short];

        			$currentTick = Tick::orderBy('tick', 'DESC')->first();
					$tick = $currentTick->tick;

					$scan_tick = $scan->tick;
					$scan_age = $tick - $scan_tick;

					$fleet_value = 0;
					$ships = [];

					$shipbuild = DB::table('ship_build')->where('race', $race)->get();
					$race_build_requirements = "";

					ForEach($shipbuild as $required_ship) {
						$race_build_requirements .= $required_ship->ship . " (" . $required_ship->percentage . "%)\n";
					}

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
						$ship_race = $ship_query->ship->race;
						$ship_value = ((($ship_metal + $ship_crystal + $ship_eonium) * $ship_amount) / 100);
						$fleet_value = $fleet_value + $ship_value;

						$ships[] = [
							'name' => $ship_name,
							'amount' => number_format($ship_amount, 0),
							'value' => $ship_value,
							'race' => $ship_race,
						];
					}

					ForEach($ships as $key => $ship) {
						$ships[$key]['percentage'] = number_format(($ship['value'] / $fleet_value) * 100, 2);
					}

					$ship_text = "";
					ForEach($ships as $key => $ship) {
						$build_percentage = DB::table('ship_build')->where('ship', $ship['name'])->get()->pluck('percentage');

						$percentage_face = "🤍";
						if($build_percentage <> "[]" && $ship['race'] == $race) {
							if($ship['percentage'] < $build_percentage[0]) {
								$percentage_face = "❤️️";
							}
							else {
								$percentage_face = "💚";
							}
						}

						$ship_text .= $ship['name'] . " " . $ship['amount'] . " (" . $ship['percentage'] . "%) " . $percentage_face . "\n";
					}

					$response .= "<strong>Ship Build Review for " . $member->name . "</strong>\n<strong>Scan Age: " . $scan_age . " (" . $scantype . ")</strong>\n<strong>Race: " . $race . "</strong>\n\n";
					$response .= "<strong>VGN requested build</strong>\n" . $race_build_requirements . "\n";
					$response .= "<strong>Ships (" . number_shorten($fleet_value, 2) . " value)</strong>\n" . $ship_text . "\n";
				}
			}
			return $response;
		}
		return "There are no members";
    }
}