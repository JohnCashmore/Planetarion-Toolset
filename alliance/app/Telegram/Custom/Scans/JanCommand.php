<?php
namespace App\Telegram\Custom\Scans;

use App\Telegram\Custom\BaseCommand;
use App\Alliance;
use App\Planet;
use App\User;
use DB;

class JanCommand extends BaseCommand
{
    protected $command = "!jan";

    public function execute()
    {
        // https://game.planetarion.com/showscan.pl?scan_grp=3ozt9l0t5ql3vow
        // $pattern = "/https:\/\/game\.planetarion\.com\/showscan.pl\?scan_grp=\w+/i";

        if(!$this->isWebUser()) return "User can not be authenticated with webby.";
        
        $time_start = microtime(true);

        $param = trim($this->text);
        if (!$param) return "Usage: !jan <scan_grp_id>";
        
        $url = "https://game.planetarion.com/showscan.pl?scan_grp=" . $param;

        try {
            $html = file_get_contents($url);
        } catch(\Exception $e) {
            return "Couldn't read url!";
        }
        $time_end_html = microtime(true);

        $ps = Planet::all();
        $users = User::all();
        $tgs = DB::table('user')->get();
        $alliances = Alliance::all();

        $time_end_slurping_dbs = microtime(true);

        $planets = [];
        foreach($ps as $planet) {
            $planets[$planet->x . ":" . $planet->y . ":" . $planet->z] = $planet;
        }

        $tg_map_planetid_to_tgid = [];
        foreach ($users as $user) {
            $tg_map_planetid_to_tgid[$user->planet_id] = $user->tg_username;
        }

        $tg_map_tgid_to_tgusername = [];
        foreach ($tgs as $tg) {
            $tg_map_tgid_to_tgusername[$tg->id] = $tg->username;
        }

        $ally_map_id_to_name = [];
        foreach($alliances as $alliance) {
            $ally_map_id_to_name[$alliance->id] = $alliance->name;
        }

        $overfill = [];
        $coords = [];
        $result_hostile_alliances = [];

        $scans = explode("<hr>", $html);

        unset($scans[0]);

        foreach($scans as $scan) {

            preg_match("/scan_id=([0-9a-zA-Z]+)/", $scan, $id);

            if(isset($id[1])) {
                $scanId = $id[1];

                preg_match('/>Scan time\: .* (\d+\:\d+\:\d+)/', $scan, $time);
                preg_match('/>([^>]+) on (\d+)\:(\d+)\:(\d+) in tick (\d+)/', $scan, $tick);

                if(!$tick) continue;

                $scanType = strtoupper($tick[1]);
                $x = $tick[2];
                $y = $tick[3];
                $z = $tick[4];
                $tick = $tick[5];
                $time = $time[1];

                // Planet gone
                if(!isset($planets[$x . ":" . $y . ":" . $z])) continue;

                $planet = $planets[$x . ":" . $y . ":" . $z];

                $planetId = ($planet) ? $planet->id : null;

                if($planetId) {
                    $scan = preg_replace("/\s+/", "", $scan);

                    if($scanType == 'JUMPGATE PROBE') {
                        preg_match_all('!<tr[^>]*><td[^>]*><a[^>]*>(\d+):(\d+):(\d+)<\/a>\(<span[^>]*>(\w+)<\/span>\)<\/td><td[^>]*>(\w+)<\/td><td[^>]*>(\w+)<\/td><td[^>]*>(\d+)<\/td><td[^>]*>(\d+)<\/td><\/tr>!', $scan, $fleets);

                        $count = count($fleets[0]);

                        for($int = 0; $int < $count; $int++) {
                            $x = $fleets[1][$int];
                            $y = $fleets[2][$int];
                            $z = $fleets[3][$int];
                            $eta = $fleets[7][$int];
                            $missionType = $fleets[5][$int];
                            $fleetName = $fleets[6][$int];
                            $shipCount = $fleets[8][$int];
                            $planetFrom = $planets[$x . ":" . $y . ":" . $z];
                            $AllianceIDFrom = ($planetFrom->alliance_id) ?? null;
                            if ($AllianceIDFrom) {
                                $AllianceNameFrom = ($ally_map_id_to_name[$AllianceIDFrom]) ?? "No fucking idea";
                            } else {
                                $AllianceNameFrom = "Unknown";
                            }
                            //$planetTo = Planet::find($planetId);

                            if(!$planetFrom) continue;
                            if($missionType == "Attack" && $eta > 7) {;
                                $coords[] = $planetFrom->x . ":" . $planetFrom->y . ":" . $planetFrom->z;
                                $overfill[] = $AllianceNameFrom;
                                $result_hostile_alliances[$AllianceNameFrom][] = $planet->x . ":" . $planet->y . ":" . $planet->z;
                            }
                        }
                    }
                }
            }
        }
        $time_end_processing_jump = microtime(true);

        $count_ally = array_count_values($overfill);
        $unique_coords = array_unique($coords);
        $count_multi_fleeters = array_count_values($coords);
        $count_unique_coords = count($unique_coords);

        $reply = [];
        $reply[] = "Analysis of: (" . $url . ")" ;
        //$reply[] = "Counting 'Attack' and eta >= 8:";
		$reply[] = "PRE-LAUNCHED INC SUMMARY ONLY";
		$reply[] = "For all INC, please click the scan link above";

        if (empty($count_ally)) return implode("\n", $reply);

        foreach($count_ally as $key => $value) {
            $summary = [];
            $count_result_hostile_alliances = array_count_values($result_hostile_alliances[$key]);
            foreach ($count_result_hostile_alliances as $coord => $iter) {
                $nick = ($planets[$coord]->nick) ?? null;
                if ($nick) {
                    $tg_id = ($tg_map_planetid_to_tgid[$planets[$coord]->id]) ?? null;
                    if ($tg_id) {
                        $ping = ($tg_map_tgid_to_tgusername[$tg_id]) ?? null;
                        $nick = ($ping) ? "@" . $ping : $nick;
                    }
                    $summary[] = $nick . " (x" . $iter . ")";
                } else {
                    $summary[] = $coord . " (x" . $iter . ")";
                }
            }
            $reply[] = "Incs from " . $key . ": (x" . $value . ") - Defenders: " . implode(" ", $summary);
        }
        $time_end_processing_reply = microtime(true);

        if (!empty($coords)) {
            $reply[] = "Total inc: (x" . count($coords) . ")";
            $reply[] = "Unique attackers' coords: (x" . $count_unique_coords . ")";
            $reply[] = "Attackers' coords for scanning: " . implode(" ", $unique_coords);

            $ret2 = [];
            $ret3 = [];
            foreach($count_multi_fleeters as $key => $value) {
                if ($value == 2) {
                    $ret2[] = $key;
                }
                if ($value == 3) {
                    $ret3[] = $key;
                }
            }
            if (!empty($ret2)) {
                $reply[] = "Two fleeters: (" . count($ret2) . ") ";
                $reply[] = "Two fleeters coords: " . implode(" ", $ret2);
            }
            if (!empty($ret3)) {
                $reply[] = "Three fleeters: (" . count($ret3) . ") ";
                $reply[] = "Three fleeters coords: " . implode(" ", $ret3);
            }
        }
        $time_end = microtime(true);
        $duration = $time_end - $time_start;
        $duration_in_seconds = date("s", $duration);
        $time_percent_html = number_format(($time_end_html - $time_start) / $duration * 100, 0);
        $time_percent_slurping_dbs = number_format(($time_end_slurping_dbs - $time_end_html) / $duration * 100, 0);
        $time_percent_processing_jump = number_format(($time_end_processing_jump - $time_end_slurping_dbs) / $duration * 100);
        $time_percent_processing_reply = number_format(($time_end_processing_reply - $time_end_processing_jump) / $duration * 100, 0);
        $reply[] = "Time spent: Html (" . $time_percent_html . "%) - Database (" . $time_percent_slurping_dbs . "%) - Jumpscan(s) (" . $time_percent_processing_jump . "%) - Reply (" . $time_percent_processing_reply . "%) - Total: " . $duration_in_seconds . " sec(s)";
        return implode("\n", $reply);
    }
}