<?php
namespace App\Telegram\Custom\Attacks;

use App\Telegram\Custom\BaseCommand;
use App;
use App\Tick;
use App\User;
use App\DevelopmentScan;

class LaunchCommand extends BaseCommand
{
    protected $command = "!launch";

    public function execute()
    {
	if(!$this->isWebUser()) return "User can not be authenticated with webby.";
		
        $params = explode(" ", $this->text);
        $valid_classes = array("fi", "co", "fr", "de", "cr", "bs");
        $base_eta = array("fi" => 12, "co" => 12, "fr" => 13, "de" => 13, "cr" => 14, "bs" => 14);

        $user = User::with('planet')->where('tg_username', $this->message->from['id'])->first();
        if(!$user->planet) return "Set your planet you fuckwit!";
        $dscan = DevelopmentScan::with('scan')->where('id', $user->planet->latest_d)->first();
        if(!$dscan) return "There's no development scan available for your planet, unsure what your TT is!";
        
        if(!$params[0]) return "Usage: !launch <class> <land_tick>, for example: !launch CO 500";

        $eta = $params[0];
        $land_tick = intval($params[1]);

        if(in_array(strtolower($eta), $valid_classes)) {
            $eta = $base_eta[strtolower($eta)];
        }
        else {
//            try {
//                $eta = intval($eta);
//		if ($eta == 0) return "Invalid class: " . $params[0];
//            }
//            catch (exception $e) {
                return "Invalid class: " . $params[0] . "\nUsage: !launch <class> <land_tick>, for example: !launch CO 500";
//            }
        }

        $currentTick = Tick::orderBy('tick', 'DESC')->first();
        $tick = $currentTick->tick;

        if(!$currentTick) return "It seems that ticks haven't started yet?";

	    $eta = $eta - $dscan->travel;

        $launch_tick = $land_tick - $eta;
        $prelaunch_tick = ($land_tick - $eta) + 1;
        $prelaunch_mod = $launch_tick - $tick;

//      return "TT-" . $dscan->travel . " | ETA " . $eta . " landing PT " . $land_tick . " (Currently " . $tick . ") must launch at PT " . $launch_tick . ", or with prelaunch tick " . $prelaunch_tick . " (Currently +" . $prelaunch_mod . ")";
		return "TT-" . $dscan->travel . " | ETA " . $eta . " landing PT " . $land_tick . " (Currently " . $tick . ") must live launch at tick " . $launch_tick . ", or launch now using prelaunch offset " . " (+" . $prelaunch_mod . ")";
    }
}