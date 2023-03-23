<?php
namespace App\Telegram\Custom\Scans;

use App\Telegram\Custom\BaseCommand;
use App\User;
use App\Tick;
use App;
use DB;
use Carbon\Carbon;
use Longman\TelegramBot\Request;
use App\DevelopmentScan;	

class ScansCommand extends BaseCommand
{
    protected $command = "!scans";

    public function execute()
    {
        $users = User::orderBy('name', 'ASC')->where(['is_enabled' => 1])->where('scanner', 1)->get();

        if(count($users)){
            $members = collect([]);

            foreach($users as $user) {
                if(env('PHP_TELEGRAM_BOT_API_KEY')) {
                    $user->tg_user = "";
                    if($user->tg_username) {
                        $user->tg_user = DB::table('user')->where('id', $user->tg_username)->first();
                        if(!$user->tg_user->username == "") {
                            $currentTime = "Not Set";
                            if(isset($user->timezone)) {
                                $currentTime = Carbon::parse(Carbon::now($user->timezone));
                            }
                            $SCANNER = collect(["name" => $user->tg_user->username, "localtime" => $currentTime, "planet_id" => $user->planet_id, "tg_username" => $user->tg_username, "webby_name" => $user->name]);
                            $members->push($SCANNER);
                            continue;
                        }
                    }
                }   
            }
        }

        $members->all();

        $starttime  = "08:00:00";
        $endtime    = "22:00:00";

        if($this->text == "all") {
            $output = "Scans needed! \nSince you hate people we will notify all scanners regardless \nof their local time.\n\n";
        }
        elseif($this->text == "amps") {
            $output = "Amps and Tech information for our scanners.\n\n";
        }
        else {
            $output = "Scans needed! \nScanners will be notified when it's between " . substr($starttime, 0, -3) . " and \n" . substr($endtime, 0, -3) . " THEIR local time. If you want to annoy every single fucking scanner type: !scans all\n\n";
        }

        ForEach($members as $scanner) {
            $name       = $scanner->get('name');
	        $tgid       = $scanner->get('tg_username');
	        $webby_name = $scanner->get('webby_name');
            $time       = $scanner->get('localtime')->format('H:i:s');
            $planet_id  = $scanner->get('planet_id');
            $planet     = DB::table('planets')->where('id', $planet_id)->get();
	        $dscan 	    = DevelopmentScan::with('scan')->where('id', $planet[0]->latest_d)->first();

            if(!$dscan) {
                $scan_type = "?";
                $amps = "?";
            }
            else {
                $scan_types  = array(0 => "P", 1 => "L", 2 => "D", 3 => "U", 4 => "N", 5 => "I", 6 => "J", 7 => "A", 8 => "M",);
                $scan_type = $scan_types[$dscan->waves];
                $amps = $dscan->wave_amplifier;
            }

            if($this->text == "all") {
                $output .= $time . " - <a href='tg://user?id=" . $tgid . "'>" . $webby_name . "</a> (" . $amps . "/" . $scan_type . ")\n";
            }
            elseif($this->text == "amps") {
                $output .= $time . " - " . $webby_name . " (" . $amps . "/" . $scan_type . ")\n";
            }
            else {
                if(($time >= $starttime) && ($time <= $endtime)) {
                    $output .= $time . " - <a href='tg://user?id=" . $tgid . "'>" . $webby_name . "</a> (" . $amps . "/" . $scan_type . ")\n";
                }
                else {
                    $output .= $time . " - " . $webby_name . " (" . $amps . "/" . $scan_type . ")\n";
                }
            }

        }

        if($this->text == "amps") {
            return $output;
        }

	    $output .= "\n<a href='https://game.planetarion.com/alliance_scans.pl?rn=#tab2'>Alliance Scans</a>";

	    // Send the same message to the scan channel, because Johnny asked...
	    Request::sendMessage([
            'chat_id' => '-1001426331402',
	        'parse_mode' => 'html',
            'text'    => $output,
        ]);

        return $output;
    }
}
