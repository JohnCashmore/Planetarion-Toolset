<?php
namespace App\Telegram\Custom;

use App\Telegram\Custom\BaseCommand;
use App\User;
use App\Ship;
use App\Planet;
use App\PlanetHistory;
use App;
use DB;

class HistoryCommand extends BaseCommand
{
    protected $command = "!history";

    public function execute()
    {
        if(!$this->isWebUser()) return "User can not be authenticated with webby.";

        preg_match("/^(\d+)[.: ](\d+)[.: ](\d+)$/", $this->text, $planet);

        $psearch = ($planet) ? $planet : false;

        if($psearch) {
            $x = $psearch[1];
            $y = $psearch[2];
            $z = $psearch[3];

            $planet = Planet::with('alliance')->where([
              'x' => $x,
              'y' => $y,
              'z' => $z
            ])->first();

            if($planet) {
                $planet_id = $planet->id;
            }
        }
        elseif(str_contains($this->text, "@")) {
            $tg_user = ltrim($this->text, '@');
            $tgdb = DB::table('user')->where('username', $tg_user)->first();
            if(!isset($tgdb)) { 
                return "No user found with this TG username: " . $tg_user . ". Use their webby username instead."; 
            }
    
            $user = User::with('planet')->where('tg_username', $tgdb->id)->first();
            if(!$user->planet) {
                return "This member has not set their planet.";
            }
            $planet_id = $user->planet_id;
        }
        elseif ($this->text) {
            $user = User::with('planet')->where('name', $this->text)->first();
            if(isset($user)) {
                if(!$user->planet) {
                    return "This member has not set their planet.";
                }
                $planet_id = $user->planet_id;
            }
        }
        else {
            $user = User::where('tg_username', $this->userId)->first();
            if(isset($user)) {
                $planet_id = $user->planet_id;
            }
        }

        if(!isset($planet_id)) {
            return "Use an actual webby nickname, @telegram_username or coords.";
        }
        
        $history = PlanetHistory::with('tick')->where('planet_id', $planet_id)->orderBy('tick', 'DESC')->take(3)->get();

        $planet_header = "";
        $planet_history = "";
        ForEach($history as $planet) {

            if($planet->change_score < 0) { 
                $change_score = $planet->change_score; 
            } else { 
                $change_score = "+" . $planet->change_score; 
            }

            if($planet->change_value < 0) { 
                $change_value = $planet->change_value; 
            } 
            else { 
                $change_value = "+" . $planet->change_value; 
            }

            if($planet->change_size < 0) { 
                $change_size = $planet->change_size; 
            } else { 
                $change_size = "+" . $planet->change_size; 
            }

            $change_xp = "+" . $planet->change_xp; 

            $planet_header = "Planet History for " . $planet->x . ":" .  $planet->y . ":" . $planet->z . "\n" . $planet->ruler_name . " of " . $planet->planet_name . " (" . $planet->race . ")\n";
            $planet_history .= "PT: " . $planet->tick . " \nScore (#" . $planet->rank_score . "): " . number_format($planet->score) . " (" . $change_score . ") \nValue (#" . $planet->rank_value . "): " . number_format($planet->value) . " (". $change_value . ") \nSize (#" . $planet->rank_size . "): " . number_format($planet->size) . " (". $change_size . ") \nXP (#" . $planet->rank_xp . "): " . $planet->xp . " (". $change_xp . ") \n\n";
        }

        $planet_link = "https://vgnpa.uk/#/planets/" . $planet_id . "/history";

        return $planet_header . "\n" . $planet_history . "<a href='" . $planet_link . "'>More history here</a>";
    }
}