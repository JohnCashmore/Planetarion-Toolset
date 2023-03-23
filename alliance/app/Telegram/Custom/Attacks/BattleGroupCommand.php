<?php
namespace App\Telegram\Custom\Attacks;

use App\Telegram\Custom\BaseCommand;
use App\User;
use App\Ship;
use App\Planet;
use App\PlanetScan;
use App\DevelopmentScan;
use App\Scan;
use App\JgpScan;
use App\Tick;
use App;
use DB;

class BattleGroupCommand extends BaseCommand
{
    protected $command = "!bg";

    public function execute()
    {
        if(!$this->isWebUser()) return "User can not be authenticated with webby.";
        if(!$this->hasTelegramUsername()) return "Cannot use this command without a Telegram username.\n\n<strong>iPhone</strong>\n- Go to Settings in Telegram and click on Edit (Profile, top right)\n- Click on Username and specify one if not set already.\n\n<strong>Android</strong>\n- Go to Settings in Telegram\n- Click on Username and specify one if not set already.";

	if(!$this->text) { return "Usage: !bg <members|targets>"; }

	$currentTick = Tick::orderBy('tick', 'DESC')->first();
	$tick = $currentTick->tick;

        $user                   = User::where('tg_username', $this->userId)->first();  
        $BG                     = DB::table('battlegroup_users')->where('user_id', $user->id)->first();

	if(!isset($BG)) {
	    return "It appears you're not in a BG?";
	}

        $BGID                   = $BG->battlegroup_id;

        $BGN                    = DB::table('battlegroups')->where('id', $BGID)->get()->pluck('name');
        $BGM                    = DB::table('battlegroup_users')->where('battlegroup_id', $BGID)->get()->pluck('user_id');

        if($this->text == "members") {
            $members                = "";
	    $members_coords		= "";
            ForEach($BGM as $member) {
                $username           = DB::table('users')->where('id', $member)->get()->pluck('name');
	        $user 		= User::with('planet')->where('name', 'LIKE', '%' . $username[0] . '%')->first();
	        $race		= "Unknown";
	        if(isset($user->planet->race)) {
	    	    $race 		= $user->planet->race;
	        }
	        $dists		= "?";
	        if(isset($user->planet->dists)) {
	    	    $dists 		= $user->planet->dists;
	        }
                $members           .= $username[0] . " (" . $user->planet->x . ":" . $user->planet->y . ":" . $user->planet->z . " - " . $race . " - D:" . $dists . ")\n";
	        $members_coords    .= $user->planet->x . ":" . $user->planet->y . ":" . $user->planet->z . " ";
            }
            return $BGN[0] .  " - Active Members\n\n" . $members . "\nLazy people scan list:\n" . $members_coords;
        }

        if($this->text == "targets") {
            $ATO                    = DB::table('attacks')->where('is_closed', 0)->get()->pluck('id');
	    if(!isset($ATO)) {
	    	return "It appears there's no open attacks.";
	    }
            $targets                = "";
            ForEach($ATO as $attack) {
                $Bookings           = DB::table('attack_bookings')->where('attack_id', $attack)->whereIn('user_id', $BGM)->get();
	        if(!isset($Bookings)) {
	    	    return "It appears there's no targets claimed.";
	        }
                ForEach($Bookings as $target) {
                    $claimed_id     = $target->user_id;
                    $claimed_by     = DB::table('users')->where('id', $claimed_id)->get()->pluck('name');
                    $target_id      = $target->attack_target_id;
                    $target_lt      = $target->land_tick;
		    $eta	    = $target_lt - $tick;
		    $target_attid   = DB::table('attacks')->where('is_closed', 0)->where('id', $target->attack_id)->get()->pluck('attack_id');
		    $target_bookid  = $target->id;
		    $booking_url    = App::make('url')->to('/') . '/#/attacks/' . $target_attid[0] . '/booking/' . $target_bookid;
                    if(isset($target->notes)) { 
                        $target_notes = str_replace('&', '&amp;', $target->notes); 
			$target_notes = str_replace('<', '<', $target_notes); 
			$target_notes = str_replace('>', '>', $target_notes); 
                    } 
                    else { 
                        $target_notes = "None"; 
                    }
                    if(isset($target->battle_calc)) { 
			$target_bcalc_name 	= "B";
                        $target_bcalc_url	= $target->battle_calc; 
                    } 
                    else { 
			$target_bcalc_name 	= "<s>B</s>";
                        $target_bcalc_url 	= "#";
                    }
                    $planet_id      	= DB::table('attack_targets')->where('id', $target_id)->get()->pluck('planet_id');
                    $planet         	= DB::table('planets')->where('id', $planet_id[0])->get();
		    $planet_url	    	= App::make('url')->to('/') . '/#/planets/' . $planet_id[0];
                    $target_x       	= $planet[0]->x;
                    $target_y       	= $planet[0]->y;
                    $target_z       	= $planet[0]->z;
		    $target_size    	= $planet[0]->size;
                    $target_planet_name	= $planet[0]->planet_name;
                    $target_ruler_name	= $planet[0]->ruler_name;
		    $target_latestp 	= $planet[0]->latest_p;
		    $target_latestd 	= $planet[0]->latest_d;
		    $target_latesta 	= $planet[0]->latest_au;
		    $target_latestj 	= $planet[0]->latest_j;


		    //$pscan 	    	    = PlanetScan::with('scan')->where('id', $target_latestp)->first();
		    //if(isset($pscan)) {
		    //	$pscan_name	    = "P";
		    //	$pscan_url	    = $pscan->scan->link;
		    //  $pscan_age	    = $tick - $pscan->scan->tick;
		    //}
		    //else {
		    //	$pscan_name	    = "<s>P</s>";
		    //	$pscan_url	    = "#";
		    //  $pscan_age	    = "#";
	            //}

		    $dscan 	    	    = DevelopmentScan::with('scan')->where('id', $target_latestd)->first();
		    if(isset($dscan)) {
		        $scan_types  	    = array(0 => "P", 1 => "L", 2 => "D", 3 => "U", 4 => "N", 5 => "I", 6 => "J", 7 => "A", 8 => "M");
		        $scan_type 	    = $scan_types[$dscan->waves];
		    	$dscan_amps	    = $dscan->wave_amplifier;
		        $dscan_age	    = $tick - $dscan->scan->tick;
		    }
		    else {
		    	$scan_type	    = "?";
		    	$dscan_amps	    = "?";
		        $dscan_age	    = "?";
	            }

		    //$ascan 	    	    = Scan::with('au')->where('id', $target_latesta)->first();
		    //if(isset($ascan)) {
		    //	$ascan_name	    = "A";
		    //	$ascan_url	    = $ascan->link;
		    //  $ascan_age	    = $tick - $ascan->tick;
		    //}
		    //else {
		    //	$ascan_name	    = "<s>A</s>";
		    //	$ascan_url	    = "#";
		    //  $ascan_age	    = "#";
	            //}

		    $jscan 	    	    = Jgpscan::with('scan')->where('id', $target_latestj)->first();
		    if(isset($jscan)) {
		    	$jscan_name	    = "J";
		    	$jscan_url	    = $jscan->scan->link;
			$jscan_age	    = $tick - $jscan->scan->tick;
		    }
		    else {
		    	$jscan_name	    = "<s>J</s>";
		    	$jscan_url	    = "#";
			$jscan_age	    = "#";
	            }

		    $targets       .= "<a href='" . $booking_url . "'>" . $target_x . ":" . $target_y . ":" . $target_z . "</a> - <a href='" . $jscan_url . "'>" . $jscan_name . "</a>(" . $jscan_age . ") <a href='" . $target_bcalc_url . "'>" . $target_bcalc_name . "</a> - " . $dscan_amps . "/" . $scan_type . "(" . $dscan_age . ") - Size: " . $target_size . "\n<strong>LT</strong>: " . $target_lt . " (eta " . $eta . ") <strong>Notes</strong>: " . $target_notes . "\n\n";
		    //$targets       .= "<a href='" . $planet_url . "'>" . $target_x . ":" . $target_y . ":" . $target_z . "</a> - <a href='" . $pscan_url . "'>" . $pscan_name . "</a>(" . $pscan_age . ") <a href='" . $dscan_url . "'>" . $dscan_name . "</a>(" . $dscan_age . ") <a href='" . $ascan_url . "'>" . $ascan_name . "</a>(" . $ascan_age . ") <a href='" . $jscan_url . "'>" . $jscan_name . "</a>(" . $jscan_age . ") <a href='" . $target_bcalc_url . "'>" . $target_bcalc_name . "</a> - Size: " . $target_size . "\n<strong>LT</strong>: " . $target_lt . " <strong>Notes</strong>: " . $target_notes . "\n\n";
                }
            }
            return $BGN[0] .  " - Active Targets\n\n" . $targets;
        }

        if($this->text == "ships") {
            $response                = "";
            ForEach($BGM as $member) {
                $username           = DB::table('users')->where('id', $member)->get()->pluck('name');
	        $user 		    = User::with('planet')->where('name', 'LIKE', '%' . $username[0] . '%')->first();
	    	$latest_au 	    = $user->planet->latest_au;

		$ascan 	    	    = Scan::with('au')->where('id', $latest_au)->first();
		if(isset($ascan)) {
		    $ascan_url	    = $ascan->link;
		    $ascan_age	    = $tick - $ascan->tick;
		}
		else {
		    $ascan_url	    = "#";
		    $ascan_age	    = "#";
	        }

                $response          .= "<a href='" . $ascan_url . "'>" . $username[0] . " (Age: " . $ascan_age . ")</a>\n";
            }
            return $BGN[0] . " - Latest AU Scans\n\n" . $response;
        }

        return "Usage: !bg <members|targets|ships>";
    }
}