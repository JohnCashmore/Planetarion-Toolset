<?php
namespace App\Telegram\Custom\Admin;

use Longman\TelegramBot\Request;
use App\Telegram\Custom\BaseCommand;
use App\User;
use App\Ship;
use DB;

class BuildCommand extends BaseCommand
{
    protected $command = "!build";

    public function execute()
    {
        if(!$this->isWebUser()) return "User can not be authenticated with webby.";

        $user = User::where('tg_username', $this->userId)->first();

        if($user->role_id <> 1) {
            return "You're not an admin....";
        }

		$string = explode(" ", $this->text);

        if(isset($string[0])) {
            if($string[0] == "overview") {
                $response = "VGN Build Overview\n\n";
                
                $build = DB::table('ship_build')->select('race')->groupBy('race')->get();

                ForEach($build as $key => $builds) {
                    $race_build = DB::table('ship_build')->where('race', $builds->race)->get();
                    $response .= "<strong>" . $builds->race . "</strong>\n";
                    ForEach($race_build as $race_ships) {
                        $response .= $race_ships->ship . " (" . $race_ships->percentage . "%)\n";
                    }
                    $response .= "\n";
                }
    
                return $response;
            }
        }

        if(!isset($string[0]) || !isset($string[1])) return "Usage: !build <ship_name> <percentage>";

		$ship_name = $string[0];
		$percentage = str_replace('%', '', $string[1]);

		if($ship_name) {
            if(Ship::where('name', 'LIKE', '%' . $ship_name . '%')->count() == 1) {
                $ship = Ship::where('name', 'LIKE', '%' . $ship_name . '%')->first();
            } else {
                if(Ship::where('name', 'LIKE', '%' . $ship_name . '%')->count() == 0) {
                    return "Can't find a ship with that name";
                } else {
                    // Check for actual name
                    if($ship = Ship::where('name', $ship_name)->count() == 1) {
                        $ship = Ship::where('name', $ship_name)->first();
                    } 
                    // Too many matches
                    else {
                        $ships = Ship::where('name', 'LIKE', '%' . $ship_name . '%')->get()->pluck('name')->toArray();
                        return "Ship name is too ambiguous (" . implode(", ", $ships) . ")";
                    }
                }
            }
        }

        $exists_check = DB::table('ship_build')->where('ship', $ship->name)->count();

        if($percentage == "del" || $percentage == "delete") {
            if($exists_check == 1) {
                $current_build = DB::table('ship_build')->where('ship', $ship->name)->first();
                DB::table('ship_build')->where('ship', $ship->name)->delete();
                return "Removed ship build: [" . $current_build->race . "] " . $current_build->ship . " (" . $current_build->percentage . "%)";
            }
            else {
                return "There is no build found for this ship: " . $ship->name;
            }
		}

        if(!is_numeric($percentage)) {
            return "Invalid percentage, use a number between 0 and 100 you dipshit.";
        }
		
		if($percentage < 0 || $percentage > 100) {
			return "Use a percentage between 0 and 100 you dipshit.";
		}

        if($exists_check == 1) {
            $build_old_percentage = DB::table('ship_build')->where('ship', $ship->name)->get()->pluck('percentage');
            DB::table('ship_build')->where('ship', $ship->name)->update(['percentage' => $percentage]);
            return "Updated ship build: [" . $ship->race . "] " . $ship->name . " (" . $build_old_percentage[0] . "% -> " . $percentage . "%)";
        }
        else {
            DB::table('ship_build')->insert(['race' => $ship->race, 'ship' => $ship->name, 'percentage' => $percentage]);
            return "Added to ship build: [" . $ship->race . "] " . $ship->name . " (" . $percentage . "%)";
        }
    }
}