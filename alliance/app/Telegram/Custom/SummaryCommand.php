<?php
namespace App\Telegram\Custom;

use App\Telegram\Custom\BaseCommand;
use App\User;
use App\Planet;
use App\Alliance;
use App\Scan;
use App\AdvancedUnitScan;
use App\Tick;
use App\Ship;
use App;
use DB;

class SummaryCommand extends BaseCommand
{
    protected $command = "!summary";

    public function execute()
    {
        if(!$this->isWebUser()) return "User can not be authenticated with webby.";

        $user = User::where('tg_username', $this->userId)->first();

        if($user->role_id <> 1) {
            return "You're not an admin....";
        }

        $alliance   = Alliance::where('name', 'like', '%' . $this->text . '%')->orWhere('nickname', $this->text)->first();
        if(!isset($alliance)) {
            return "There is no alliance with name or alias: " . $this->text;
        }

        $currentTick            = Tick::orderBy('tick', 'DESC')->first();
        $tick                   = $currentTick->tick;

        $planets                = Planet::where('alliance_id', $alliance->id)->orderBy('x', 'ASC')->orderBy('y', 'ASC')->orderBy('z', 'ASC')->get();

        $noscan = collect([]);
        $oldscan = collect([]);

	// RACES
        $ter_count = Planet::where([['alliance_id', $alliance->id],['race', 'Ter'],])->count();
        $cat_count = Planet::where([['alliance_id', $alliance->id],['race', 'Cat'],])->count();
        $xan_count = Planet::where([['alliance_id', $alliance->id],['race', 'Xan'],])->count();
        $zik_count = Planet::where([['alliance_id', $alliance->id],['race', 'Zik'],])->count();
        $kin_count = Planet::where([['alliance_id', $alliance->id],['race', 'Kin'],])->count();
		$sly_count = Planet::where([['alliance_id', $alliance->id],['race', 'Sly'],])->count();

	// SHIPS
	$fi_class = Ship::where('class', 'Fighter')->get();
	$fi_counter = Ship::where('t1', 'Fighter')->orWhere('t2', 'Fighter')->orWhere('t3', 'Fighter')->get();
	$fi_class_value = 0;
	$fi_counter_dmg = 0;

	$co_class = Ship::where('class', 'Corvette')->get();
	$co_counter = Ship::where('t1', 'Corvette')->orWhere('t2', 'Corvette')->orWhere('t3', 'Corvette')->get();
	$co_class_value = 0;
	$co_counter_dmg = 0;

	$de_class = Ship::where('class', 'Destroyer')->get();
	$de_counter = Ship::where('t1', 'Destroyer')->orWhere('t2', 'Destroyer')->orWhere('t3', 'Destroyer')->get();
	$de_class_value = 0;
	$de_counter_dmg = 0;

	$fr_class = Ship::where('class', 'Frigate')->get();
	$fr_counter = Ship::where('t1', 'Frigate')->orWhere('t2', 'Frigate')->orWhere('t3', 'Frigate')->get();
	$fr_class_value = 0;
	$fr_counter_dmg = 0;

	$bs_class = Ship::where('class', 'Battleship')->get();
	$bs_counter = Ship::where('t1', 'Battleship')->orWhere('t2', 'Battleship')->orWhere('t3', 'Battleship')->get();
	$bs_class_value = 0;
	$bs_counter_dmg = 0;

	$cr_class = Ship::where('class', 'Cruiser')->get();
	$cr_counter = Ship::where('t1', 'Cruiser')->orWhere('t2', 'Cruiser')->orWhere('t3', 'Cruiser')->get();
	$cr_class_value = 0;
	$cr_counter_dmg = 0;

	$fi_emp_estimated_damage = 0;
	$co_emp_estimated_damage = 0;
	$de_emp_estimated_damage = 0;
	$fr_emp_estimated_damage = 0;
	$bs_emp_estimated_damage = 0;
	$cr_emp_estimated_damage = 0;

        $result = "<u>Summary " . $alliance->name . "</u>\n";
        
        ForEach($planets as $planet) {
            $ascan = Scan::with('au')->where('id', $planet->latest_au)->first();

            $nickname = "";
            if(isset($planet->nick)) {
                $nickname = $planet->nick;
            }
                        
            if(!$ascan) {
                $info           = collect(["nickname" => $nickname, "x" => $planet->x, "y" => $planet->y, "z" => $planet->z]); 
                $noscan->push($info);
                continue; 
            }

            $scanage            = $tick - $ascan->tick;
            if($scanage > 3) {
                $info           = collect(["nickname" => $nickname, "x" => $planet->x, "y" => $planet->y, "z" => $planet->z, "scanage" => $scanage]);    
                $oldscan->push($info);
            }

	    $ships = $ascan->au;
            ForEach($ships as $ship) {
            	$ship_id = $ship->ship_id;
		$ship_amount = $ship->amount;

		$ship_query = AdvancedUnitScan::with('ship')->where('ship_id', $ship_id)->first();
		$ship_name = $ship_query->ship->name;
		$ship_type = $ship_query->ship->type;
		$ship_class = $ship_query->ship->class;
		$ship_t1 = $ship_query->ship->t1;
		$ship_t2 = $ship_query->ship->t2;
		$ship_t3 = $ship_query->ship->t3;
		$ship_damage = $ship_query->ship->damage;
		$ship_armor = $ship_query->ship->armor;
		$ship_empres = $ship_query->ship->empres;
		$ship_guns = $ship_query->ship->guns;
		$ship_metal = $ship_query->ship->metal;
		$ship_crystal = $ship_query->ship->crystal;
		$ship_eonium = $ship_query->ship->eonium;

		if($ship_type == "Structure" || $ship_type == "Roids" || $ship_type == "Resources") {
		    continue;
		}

		$damage = $ship_damage * $ship_amount;
	        $shipValue = (($ship_metal + $ship_crystal + $ship_eonium) * $ship_amount) / 100;
		$shots = $ship_guns * $ship_amount;

		if($ship_class == "Fighter") {
		    $fi_class_value = $fi_class_value + $shipValue;
		}
		if($ship_class == "Corvette") {
		    $co_class_value = $co_class_value + $shipValue;
		}
		if($ship_class == "Destroyer") {
		    $de_class_value = $de_class_value + $shipValue;
		}
		if($ship_class == "Frigate") {
		    $fr_class_value = $fr_class_value + $shipValue;
		}
		if($ship_class == "Battleship") {
		    $bs_class_value = $bs_class_value + $shipValue;
		}
		if($ship_class == "Cruiser") {
		    $cr_class_value = $cr_class_value + $shipValue;
		}

		$t2dmg = 0.7;
		$t3dmg = 0.5;

		if($ship_type == "EMP") {
		    if($ship_t1 == "Fighter" || $ship_t2 == "Fighter" || $ship_t3 == "Fighter") {
		    	$fi_killed = 0;
			$fi_armor = 0;
		    	$fi_ships = 0;

		    	$targettingt1 = Ship::where('class', $ship_t1)->get();
		    	foreach($targettingt1 as $tgt) {
			    $fi_killed = (int) ($shots) * (100 - $tgt->empres) / 100;
			    $fi_armor = $fi_armor + ($fi_killed * $tgt->armor);
			    $fi_ships = $fi_ships + 1;
		    	}

		    	$targettingt2 = Ship::where('class', $ship_t2)->get();
		    	foreach($targettingt2 as $tgt) {
			    $fi_killed = (int) ($shots) * (100 - $tgt->empres) / 100;
			    $fi_killed = $fi_killed * $t2dmg;
			    $fi_armor = $fi_armor + ($fi_killed * $tgt->armor);
			    $fi_ships = $fi_ships + 1;
		    	}

		    	$targettingt3 = Ship::where('class', $ship_t3)->get();
		    	foreach($targettingt3 as $tgt) {
			    $fi_killed = (int) ($shots) * (100 - $tgt->empres) / 100;
			    $fi_killed = $fi_killed * $t3dmg;
			    $fi_armor = $fi_armor + ($fi_killed * $tgt->armor);
			    $fi_ships = $fi_ships + 1;
		    	}

		    	$fi_emp_estimated_damage = $fi_armor / $fi_ships;
			$fi_counter_dmg = $fi_counter_dmg + $fi_emp_estimated_damage;
		    }

		    if($ship_t1 == "Corvette" || $ship_t2 == "Corvette" || $ship_t3 == "Corvette") {
		    	$co_killed = 0;
			$co_armor = 0;
		    	$co_ships = 0;

		    	$targettingt1 = Ship::where('class', $ship_t1)->get();
		    	foreach($targettingt1 as $tgt) {
			    $co_killed = (int) ($shots) * (100 - $tgt->empres) / 100;
			    $co_armor = $co_armor + ($co_killed * $tgt->armor);
			    $co_ships = $co_ships + 1;
		    	}

		    	$targettingt2 = Ship::where('class', $ship_t2)->get();
		    	foreach($targettingt2 as $tgt) {
			    $co_killed = (int) ($shots) * (100 - $tgt->empres) / 100;
			    $co_killed = $co_killed * $t2dmg;
			    $co_armor = $co_armor + ($co_killed * $tgt->armor);
			    $co_ships = $co_ships + 1;
		    	}

		    	$targettingt3 = Ship::where('class', $ship_t3)->get();
		    	foreach($targettingt3 as $tgt) {
			    $co_killed = (int) ($shots) * (100 - $tgt->empres) / 100;
			    $co_killed = $co_killed * $t3dmg;
			    $co_armor = $co_armor + ($co_killed * $tgt->armor);
			    $co_ships = $co_ships + 1;
		    	}

		    	$co_emp_estimated_damage = $co_armor / $co_ships;
			$co_counter_dmg = $co_counter_dmg + $co_emp_estimated_damage;
		    }

		    if($ship_t1 == "Destroyer" || $ship_t2 == "Destroyer" || $ship_t3 == "Destroyer") {
		    	$de_killed = 0;
			$de_armor = 0;
		    	$de_ships = 0;

		    	$targettingt1 = Ship::where('class', $ship_t1)->get();
		    	foreach($targettingt1 as $tgt) {
			    $de_killed = (int) ($shots) * (100 - $tgt->empres) / 100;
			    $de_armor = $de_armor + ($de_killed * $tgt->armor);
			    $de_ships = $de_ships + 1;
		    	}

		    	$targettingt2 = Ship::where('class', $ship_t2)->get();
		    	foreach($targettingt2 as $tgt) {
			    $de_killed = (int) ($shots) * (100 - $tgt->empres) / 100;
			    $de_killed = $de_killed * $t2dmg;
			    $de_armor = $de_armor + ($de_killed * $tgt->armor);
			    $de_ships = $de_ships + 1;
		    	}

		    	$targettingt3 = Ship::where('class', $ship_t3)->get();
		    	foreach($targettingt3 as $tgt) {
			    $de_killed = (int) ($shots) * (100 - $tgt->empres) / 100;
			    $de_killed = $de_killed * $t3dmg;
			    $de_armor = $de_armor + ($de_killed * $tgt->armor);
			    $de_ships = $de_ships + 1;
		    	}

		    	$de_emp_estimated_damage = $de_armor / $de_ships;
			$de_counter_dmg = $de_counter_dmg + $de_emp_estimated_damage;
		    }

		    if($ship_t1 == "Frigate" || $ship_t2 == "Frigate" || $ship_t3 == "Frigate") {
		    	$fr_killed = 0;
			$fr_armor = 0;
		    	$fr_ships = 0;

		    	$targettingt1 = Ship::where('class', $ship_t1)->get();
		    	foreach($targettingt1 as $tgt) {
			    $fr_killed = (int) ($shots) * (100 - $tgt->empres) / 100;
			    $fr_armor = $fr_armor + ($fr_killed * $tgt->armor);
			    $fr_ships = $fr_ships + 1;
		    	}

		    	$targettingt2 = Ship::where('class', $ship_t2)->get();
		    	foreach($targettingt2 as $tgt) {
			    $fr_killed = (int) ($shots) * (100 - $tgt->empres) / 100;
			    $fr_killed = $fr_killed * $t2dmg;
			    $fr_armor = $fr_armor + ($fr_killed * $tgt->armor);
			    $fr_ships = $fr_ships + 1;
		    	}

		    	$targettingt3 = Ship::where('class', $ship_t3)->get();
		    	foreach($targettingt3 as $tgt) {
			    $fr_killed = (int) ($shots) * (100 - $tgt->empres) / 100;
			    $fr_killed = $fr_killed * $t3dmg;
			    $fr_armor = $fr_armor + ($fr_killed * $tgt->armor);
			    $fr_ships = $fr_ships + 1;
		    	}

		    	$fr_emp_estimated_damage = $fr_armor / $fr_ships;
			$fr_counter_dmg = $fr_counter_dmg + $fr_emp_estimated_damage;
		    }

		    if($ship_t1 == "Battleship" || $ship_t2 == "Battleship" || $ship_t3 == "Battleship") {
		    	$bs_killed = 0;
			$bs_armor = 0;
		    	$bs_ships = 0;

		    	$targettingt1 = Ship::where('class', $ship_t1)->get();
		    	foreach($targettingt1 as $tgt) {
			    $bs_killed = (int) ($shots) * (100 - $tgt->empres) / 100;
			    $bs_armor = $bs_armor + ($bs_killed * $tgt->armor);
			    $bs_ships = $bs_ships + 1;
		    	}

		    	$targettingt2 = Ship::where('class', $ship_t2)->get();
		    	foreach($targettingt2 as $tgt) {
			    $bs_killed = (int) ($shots) * (100 - $tgt->empres) / 100;
			    $bs_killed = $bs_killed * $t2dmg;
			    $bs_armor = $bs_armor + ($bs_killed * $tgt->armor);
			    $bs_ships = $bs_ships + 1;
		    	}

		    	$targettingt3 = Ship::where('class', $ship_t3)->get();
		    	foreach($targettingt3 as $tgt) {
			    $bs_killed = (int) ($shots) * (100 - $tgt->empres) / 100;
			    $bs_killed = $bs_killed * $t3dmg;
			    $bs_armor = $bs_armor + ($bs_killed * $tgt->armor);
			    $bs_ships = $bs_ships + 1;
		    	}

		    	$bs_emp_estimated_damage = $bs_armor / $bs_ships;
			$bs_counter_dmg = $bs_counter_dmg + $bs_emp_estimated_damage;
		    }

		    if($ship_t1 == "Cruiser" || $ship_t2 == "Cruiser" || $ship_t3 == "Cruiser") {
		    	$cr_killed = 0;
			$cr_armor = 0;
		    	$cr_ships = 0;

		    	$targettingt1 = Ship::where('class', $ship_t1)->get();
		    	foreach($targettingt1 as $tgt) {
			    $cr_killed = (int) ($shots) * (100 - $tgt->empres) / 100;
			    $cr_armor = $cr_armor + ($cr_killed * $tgt->armor);
			    $cr_ships = $cr_ships + 1;
		    	}

		    	$targettingt2 = Ship::where('class', $ship_t2)->get();
		    	foreach($targettingt2 as $tgt) {
			    $cr_killed = (int) ($shots) * (100 - $tgt->empres) / 100;
			    $cr_killed = $cr_killed * $t2dmg;
			    $cr_armor = $cr_armor + ($cr_killed * $tgt->armor);
			    $cr_ships = $cr_ships + 1;
		    	}

		    	$targettingt3 = Ship::where('class', $ship_t3)->get();
		    	foreach($targettingt3 as $tgt) {
			    $cr_killed = (int) ($shots) * (100 - $tgt->empres) / 100;
			    $cr_killed = $cr_killed * $t3dmg;
			    $cr_armor = $cr_armor + ($cr_killed * $tgt->armor);
			    $cr_ships = $cr_ships + 1;
		    	}

		    	$cr_emp_estimated_damage = $cr_armor / $cr_ships;
			$cr_counter_dmg = $cr_counter_dmg + $cr_emp_estimated_damage;
		    }
		}

		if($ship_t1 == "Fighter") {
		    $fi_counter_dmg = $fi_counter_dmg + $damage;
		}
		if($ship_t2 == "Fighter") {
		    $fi_counter_dmg = $fi_counter_dmg + ($damage * $t2dmg);
		}
		if($ship_t3 == "Fighter") {
		    $fi_counter_dmg = $fi_counter_dmg + ($damage * $t3dmg);
		}

		if($ship_t1 == "Corvette") {
		    $co_counter_dmg = $co_counter_dmg + $damage;
		}
		if($ship_t2 == "Corvette") {
		    $co_counter_dmg = $co_counter_dmg + ($damage * $t2dmg);
		}
		if($ship_t3 == "Corvette") {
		    $co_counter_dmg = $co_counter_dmg + ($damage * $t3dmg);
		}

		if($ship_t1 == "Destroyer") {
		    $de_counter_dmg = $de_counter_dmg + $damage;
		}
		if($ship_t2 == "Destroyer") {
		    $de_counter_dmg = $de_counter_dmg + ($damage * $t2dmg);
		}
		if($ship_t3 == "Destroyer") {
		    $de_counter_dmg = $de_counter_dmg + ($damage * $t3dmg);
		}

		if($ship_t1 == "Frigate") {
		    $fr_counter_dmg = $fr_counter_dmg + $damage;
		}
		if($ship_t2 == "Frigate") {
		    $fr_counter_dmg = $fr_counter_dmg + ($damage * $t2dmg);
		}
		if($ship_t3 == "Frigate") {
		    $fr_counter_dmg = $fr_counter_dmg + ($damage * $t3dmg);
		}

		if($ship_t1 == "Battleship") {
		    $bs_counter_dmg = $bs_counter_dmg + $damage;
		}
		if($ship_t2 == "Battleship") {
		    $bs_counter_dmg = $bs_counter_dmg + ($damage * $t2dmg);
		}
		if($ship_t3 == "Battleship") {
		    $bs_counter_dmg = $bs_counter_dmg + ($damage * $t3dmg);
		}

		if($ship_t1 == "Cruiser") {
		    $cr_counter_dmg = $cr_counter_dmg + $damage;
		}
		if($ship_t2 == "Cruiser") {
		    $cr_counter_dmg = $cr_counter_dmg + ($damage * $t2dmg);
		}
		if($ship_t3 == "Cruiser") {
		    $cr_counter_dmg = $cr_counter_dmg + ($damage * $t3dmg);
		}
	    }
        }

        $noscan->all();
        $oldscan->all();

        $oldscan_result         = "<strong>Old Scans (+3 tick)</strong>: \n";
        ForEach($oldscan as $record) {
            $oldscan_result    .= $record->get('x') . ":" . $record->get('y') . ":" . $record->get('z') . ", ";
        }

        $noscan_result = "<strong>No Scans</strong>: \n";
        ForEach($noscan as $record) {
            $noscan_result     .= $record->get('x') . ":" . $record->get('y') . ":" . $record->get('z') . ", ";
        }

        $races_result = "<strong>Races</strong>\nTer: " . $ter_count . "\nCat: " . $cat_count . "\nXan: " . $xan_count . "\nZik: " . $zik_count . "\nKin: " . $kin_count . "\nSly: " . $sly_count . "\n";
        $class_result = "<strong>Classes (value)</strong>\nFI: " . number_format($fi_class_value) . "\nCO: " . number_format($co_class_value) . "\nDE: " . number_format($de_class_value) . "\nFR: " . number_format($fr_class_value) . "\nBS: " . number_format($bs_class_value) . "\nCR: " . number_format($cr_class_value) . "\n";
        $anticlass_result = "<strong>Targetting Damage</strong>\nFI: " . number_format($fi_counter_dmg) . "\nCO: " . number_format($co_counter_dmg) . "\nDE: " . number_format($de_counter_dmg) . "\nFR: " . number_format($fr_counter_dmg) . "\nBS: " . number_format($bs_counter_dmg) . "\nCR: " . number_format($cr_counter_dmg) . "\n";

        return $result . "\n" . $races_result . "\n" . $class_result . "\n" . $anticlass_result . "\n\n" . substr($oldscan_result, 0, -2) . "\n\n" . substr($noscan_result, 0, -2) . "\n\n";
    }
}