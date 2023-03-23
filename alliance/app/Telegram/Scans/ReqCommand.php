<?php
namespace App\Telegram\Custom\Scans;

use App\Telegram\Custom\BaseCommand;
use App\Attack;
use App;
use App\Tick;
use App\User;
use App\Planet;
use App\Services\CreateScanRequest;
use App\PlanetScan;
use App\DevelopmentScan;
use App\NewsScan;
use App\Scan;
use App\JgpScan;
use App\MilitaryScan;
use DB;

class ReqCommand extends BaseCommand
{
    protected $command = "!req";

    public function execute()
    {
        $string = explode(" ", $this->text);

        if(!isset($string[0]) || !isset($string[1])) return "usage: !req <coords> <types eg. pda>";

        if(!$this->isWebUser()) return "User can not be authenticated with webby.";

        $user = User::where('tg_username', $this->userId)->first();

        preg_match("/^(\d+)[.: ](\d+)[.: ](\d+)$/", $string[0], $planet);

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

            if(!$planet) {
                return sprintf("No planet found at %d:%d:%d", $x, $y, $z);
            }
        }

        $currentTick = Tick::orderBy('tick', 'DESC')->first();
		$tick = $currentTick->tick;

		$requested_scans = str_split(strtolower($string[1]));
		$scantypes = "";

		ForEach($requested_scans as $scantype) {
			$types = array("p", "d", "u", "n", "j", "a", "m");
			if(!in_array(strtolower($scantype), $types)) {
				continue;
			}

			$age = $tick;
			$link = "#";

			$request_check = DB::table('scan_requests')->where([
						['planet_id', '=', $planet->id],
						['scan_type', '=', $scantype]
						])->WhereNull('scan_id')->first();

			if($scantype == 'p') {
				$last_scan = $planet->latest_p;
				if($last_scan) {
					$pscan = PlanetScan::with('scan')->where('id', $last_scan)->first();
					$ptick = $pscan->scan->tick;
					$age = $tick - $ptick;
					$link = $pscan->scan->link;
				}

				if($age < 2) {
					$response = "Recent scan available dipshit 🖕";
				}
				else {
					if(isset($request_check)) {
						$response = "Already requested, please check later 🖕";
						$scantypes .= "<a href='" . $link . "'>PLANET SCAN</a> (Age:" . $age . ") - " . $response . "\n";
						continue;
					}
					$request = App::make(CreateScanRequest::class);
					$response = $request->setX($x)->setY($y)->setZ($z)->setScanType(str_split(strtolower($scantype)))->setTick($tick)->setUserId($user->id)->execute();
				}
			}
			elseif($scantype == 'l') {
				$age = $tick;
				$link = "#";
				$response = "You can't request L scans dipshit 🖕";
			}
			elseif($scantype == 'd') {
				$last_scan = $planet->latest_d;
				if($last_scan) {
					$dscan = DevelopmentScan::with('scan')->where('id', $last_scan)->first();
					$dtick = $dscan->scan->tick;
					$age = $tick - $dtick;
					$link = $dscan->scan->link;
				}

				if($age < 2) {
					$response = "Recent scan available dipshit 🖕";
				}
				else {
					if(isset($request_check)) {
						$response = "Already requested, please check later.";
						$scantypes .= "<a href='" . $link . "'>DEVELOPMENT SCAN</a> (Age:" . $age . ") - " . $response . "\n";
						continue;
					}
					$request = App::make(CreateScanRequest::class);
					$response = $request->setX($x)->setY($y)->setZ($z)->setScanType(str_split(strtolower($scantype)))->setTick($tick)->setUserId($user->id)->execute();
				}
			}
			elseif($scantype == 'u') {
				$last_scan = $planet->latest_u;
				if($last_scan) {
					$uscan = Scan::with('u')->where('id', $last_scan)->first();
					$utick = $uscan->tick;
					$age = $tick - $utick;
					$link = $uscan->link;
				}

				if($age < 2) {
					$response = "Recent scan available dipshit 🖕";
				}
				else {
					if(isset($request_check)) {
						$response = "Already requested, please check later.";
						$scantypes .= "<a href='" . $link . "'>UNIT SCAN</a> (Age:" . $age . ") - " . $response . "\n";
						continue;
					}
					$request = App::make(CreateScanRequest::class);
					$response = $request->setX($x)->setY($y)->setZ($z)->setScanType(str_split(strtolower($scantype)))->setTick($tick)->setUserId($user->id)->execute();
				}
			}
			elseif($scantype == 'n') {
				$last_scan = $planet->latest_n;
				if($last_scan) {
					$nscan = NewsScan::with('scan')->where('id', $last_scan)->first();
					$ntick = $nscan->scan->tick;
					$age = $tick - $ntick;
					$link = $nscan->scan->link;
				}

				if($age < 0) {
					$response = "Recent scan available dipshit 🖕";
				}
				else {
					if(isset($request_check)) {
						$response = "Already requested, please check later.";
						$scantypes .= "<a href='" . $link . "'>NEWS SCAN</a> (Age:" . $age . ") - " . $response . "\n";
						continue;
					}
					$request = App::make(CreateScanRequest::class);
					$response = $request->setX($x)->setY($y)->setZ($z)->setScanType(str_split(strtolower($scantype)))->setTick($tick)->setUserId($user->id)->execute();
				}
			}
			elseif($scantype == 'i') {
				$age = $tick;
				$link = "#";
				$response = "You can't request I scans dipshit 🖕";
			}
			elseif($scantype == 'j') {
				$last_scan = $planet->latest_j;
				if($last_scan) {
					$jscan = Jgpscan::with('scan')->where('id', $last_scan)->first();
					$jtick = $jscan->scan->tick;
					$age = $tick - $jtick;
					$link = $jscan->scan->link;
				}
				if(isset($request_check)) {
					$response = "Already requested, please check later.";
					$scantypes .= "<a href='" . $link . "'>JUMPGATE PROBE</a> (Age:" . $age . ") - " . $response . "\n";
					continue;
				}

				$request = App::make(CreateScanRequest::class);
				$response = $request->setX($x)->setY($y)->setZ($z)->setScanType(str_split(strtolower($scantype)))->setTick($tick)->setUserId($user->id)->execute();
			}
			elseif($scantype == 'm') {
                $last_scan = $planet->latest_m;
                if($last_scan) {
                    $mscan = Scan::with('m')->where('id', $last_scan)->first();
                    $mtick = $mscan->tick;
                    $age = $tick - $mtick;
                    $link = $mscan->link;
                }
				
				if($age < 0) {
					$response = "Recent scan available dipshit 🖕";
				}
				if(isset($request_check)) {
					$response = "Already requested, please check later.";
					$scantypes .= "<a href='" . $link . "'>MILITARY SCAN</a> (Age:" . $age . ") - " . $response . "\n";
					continue;
				}

				$request = App::make(CreateScanRequest::class);
				$response = $request->setX($x)->setY($y)->setZ($z)->setScanType(str_split(strtolower($scantype)))->setTick($tick)->setUserId($user->id)->execute();
			}
			elseif($scantype == 'a') {
				$last_scan = $planet->latest_au;
				if($last_scan) {
					$ascan = Scan::with('au')->where('id', $last_scan)->first();
					$atick = $ascan->tick;
					$age = $tick - $atick;
					$link = $ascan->link;
				}

				if($age < 2) {
					$response = "Recent scan available dipshit 🖕";
				}
				else {
						if(isset($request_check)) {
					$response = "Already requested, please check later.";
					$scantypes .= "<a href='" . $link . "'>ADVANCED UNIT SCAN</a> (Age:" . $age . ") - " . $response . "\n";
					continue;
				}
					$request = App::make(CreateScanRequest::class);
					$response = $request->setX($x)->setY($y)->setZ($z)->setScanType(str_split(strtolower($scantype)))->setTick($tick)->setUserId($user->id)->execute();
				}
			}
			$linktext = "";
			switch (strtolower($scantype)) {
				case "p": $linktext = "PLANET SCAN"; break;
				case "d": $linktext = "DEVELOPMENT SCAN"; break;
				case "u": $linktext = "UNIT SCAN"; break;
				case "n": $linktext = "NEWS SCAN"; break;
				case "j": $linktext = "JUMPGATE PROBE"; break;
				case "a": $linktext = "ADVANCED UNIT SCAN"; break;
				case "m": $linktext = "MILITARY SCAN"; break;
			}

			$scantypes .= "<a href='" . $link . "'>" . $linktext . "</a> (Age:" . $age . ") - " . $response . "\n";
		}

        return "Scan request(s) for " . $x . ":" . $y . ":" . $z . ":\n" . $scantypes . "\nTo cancel your request type !cancel <request_id>";
    }
}
