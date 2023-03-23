<?php
namespace App\Telegram\Custom;

use App\Telegram\Custom\BaseCommand;
use App\Alliance;
use App\Planet;
use App\DevelopmentScan;
use App\Scan;
use App\Tick;
use App;

class MiningcapCommand extends BaseCommand
{
    protected $command = "!miningcap";

    public function execute()
    {
        if(!$this->isWebUser()) return "User can not be authenticated with webby.";

        $string = explode(" ", $this->text);

        if (!$string[0]) return "Usage: !miningcap <tag>";

        $currentTick = Tick::orderBy('tick', 'DESC')->first();
        $Now = ($currentTick->tick) ?? 0;

        $alliance = Alliance::where('name', 'like', '%' . $string[0] . '%')->orWhere('nickname', $string[0])->first();
        if (!$alliance) return "Cannot find alliance with name of nickname: " . $string[0];

        $planets = Planet::where('alliance_id', $alliance->id)->get();
        $hct_mining = array(100, 200, 300, 500, 750, 1000 , 1250, 1500, 2000, 2500, 3000, 3500, 4500, 5500, 6500, 8000, 10000);
        $data = [];
        $forego_coords = [];
        $eightypercent_coords = [];
        $problematic_coords = [];

        foreach ($planets as $planet) {
            $nick = (($planet->nick === "") || ($planet->nick === null)) ? "Unknown" : $planet->nick;
            $latest_d = DevelopmentScan::where('id', $planet->latest_d)->first();
            $scan_id = $latest_d->scan_id ?? null;
            $scan = Scan::where('id', $scan_id)->first();
            $scanTick = ($scan->tick) ?? 0;
            $hct_research = ($latest_d->mining) ?? null;
            
            if (!$scan_id) {
                $problematic_coords[] = $planet->x . ":" . $planet->y . ":" . $planet->z;
                continue;
            }
            if (isset($hct_research) && ($hct_research >= 0 && $hct_research <= 16)) {
                $can_mine = $hct_mining[$hct_research];
                if ($planet->size > $can_mine) {
                    $above_limit = $planet->size - $can_mine;
                    $forego = (0.75 * $planet->size > $can_mine) ? 1 : 0;
                    $eightypercent = (0.75 * $planet->size > 0.8 * $can_mine) ? 1 : 0;
                    $age = $Now - $scanTick;
                    $data[] = $planet->x . ":" . $planet->y . ":" . $planet->z . " (" . $nick . ") - Above limit: +" . $above_limit . " - D scan age: " . $age;
                    if ($forego) {
                        $forego_coords[] = ($nick === "Unknown") ? $planet->x . ":" . $planet->y . ":" . $planet->z : $nick;
                    }
                    if ($eightypercent) {
                        $eightypercent_coords[] = ($nick === "Unknown") ? $planet->x . ":" . $planet->y . ":" . $planet->z : $nick;
                    }
                }

            } else { 
                continue; 
            }
        }
        $response = "Above miningcap for tag: " . $alliance->name . "\n";
        $response .= implode("\n", $data);
        if ($forego_coords) {
            $response .= "\nCan forego a wave: " . implode(" ", $forego_coords);
        }
        if ($eightypercent_coords) {
            $response .= "\nAbove 80% miningcap after 1 wave: " . implode(" ", $eightypercent_coords);
        }
        if ($problematic_coords) {
            $response .= "\nProblematic coords: " . implode(" ", $problematic_coords);
        }

        return $response;
    }
}