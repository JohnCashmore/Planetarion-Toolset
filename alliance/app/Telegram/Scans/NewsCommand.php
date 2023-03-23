<?php
namespace App\Telegram\Custom\Scans;

use App\Telegram\Custom\BaseCommand;
use App\User;
use App\NewsScan;
use App\Planet;
use App;
use App\Tick;

class NewsCommand extends BaseCommand
{
    protected $command = "!nscan";

    public function execute()
    {
	if(!$this->isWebUser()) return "User can not be authenticated with webby.";

        preg_match("/^(\d+)[.: ](\d+)[.: ](\d+)[ ]?(l)?$/", $this->text, $coords);

        $psearch = ($coords) ? $coords : false;

        if(!$psearch) { return "Usage: !nscan <x:y:z>"; }

            if($psearch) {
                $x = $psearch[1];
                $y = $psearch[2];
                $z = $psearch[3];
                $l = isset($psearch[4]);

                $coords = Planet::where([
                'x' => $x,
                'y' => $y,
                'z' => $z
                ])->first();
        }

        if(!$coords) { return "No such planet"; }

        $nscan = NewsScan::with('scan')->where('id', $coords->latest_n)->first();

        if(!$nscan) { return "There's no news scan for this planet.\nBetter request one and add it to the webby."; }

        $currentTick = Tick::orderBy('tick', 'DESC')->first();
	    $tick = $currentTick->tick;
	    $age = $tick - $nscan->scan->tick;
        
        return "<a href='" . $nscan->scan->link . "'>News Scan on " . $coords->x . ":" . $coords->y . ":" . $coords->z . " in tick " . $nscan->scan->tick . " (Age:" . $age . ")</a>"; 
    }
}