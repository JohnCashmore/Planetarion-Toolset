<?php
namespace App\Telegram\Custom\Attacks;

use App\Telegram\Custom\BaseCommand;
use App\User;
use App\Planet;
use App;

class MaxcapCommand extends BaseCommand
{
    protected $command = "!maxcap";

    public function execute()
    {
        if(!$this->isWebUser()) return "User can not be authenticated with webby.";
        
        $user = User::with('planet')->where('tg_username', $this->message->from['id'])->first();
        if(!$user) {
            return "You must link your TG account to your web user using !setnick <web username>.";
        }
        if(!$user->planet) {
            return "You haven't set your coords on webby, use !setplanet <x:y:z>.";
        }

        $attacker_x = $user->planet->x;
        $attacker_y = $user->planet->y;
        $attacker_z = $user->planet->z;
        $attacker_value = $user->planet->value;
        
        preg_match("/^(\d+)[.: ](\d+)[.: ](\d+)[ ]?(\d+)?$/", $this->text, $target);

        $psearch = ($target) ? $target : false;

	if(!$psearch) {
	    return "Usage: !maxcap <x:y:z> [waves]";
	}

        if($psearch) {
            $x = $psearch[1];
            $y = $psearch[2];
            $z = $psearch[3];
	    $w = 1;
	    if(isset($psearch[4])) {
	    	$w = $psearch[4];
	    }

            $target = Planet::where([
              'x' => $x,
              'y' => $y,
              'z' => $z
            ])->first();
        }

        if(!$target) {
            return sprintf("No target found at %d:%d:%d", $x, $y, $z);
        }

	if($w > 10) { return "Stop fucking around dipshit"; }

        $target_x = $target->x;
        $target_y = $target->y;
        $target_z = $target->z;
        $target_value = $target->value;
        $target_size = $target->size;

        $mincap     = 0.00;
        $maxcap     = 0.25;
        $modifier   = (floatval($target_value)/floatval($attacker_value)) ** 0.5;
        $caprate    = max($mincap, min($maxcap * $modifier, $maxcap));
        $capped     = round($target_size * $caprate);

	$size	    = $target_size;
        $percentage = number_format(($capped * 100 / $target_size), 2);

        $response = "Maxcap for " . $attacker_x . ":" . $attacker_y . ":" . $attacker_z . " attacking " . $target_x . ":" . $target_y . ":" . $target_z . "\n";

	$waves = 1;
	while($waves <= $w) {
  		$response .= "Wave " . $waves . " - " . $capped . "/" . $size . " (" . $percentage . "%)\n";
		$size = $size - $capped;
		$capped = round($size * $caprate);
		$percentage = number_format(($capped * 100 / $size), 2);
  		$waves++;
	} 
	
	return $response;
    }
}