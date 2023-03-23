<?php
namespace App\Telegram\Custom;

use App\Telegram\Custom\BaseCommand;
use App\Tick;
use App;

class BonusCommand extends BaseCommand
{
    protected $command = "!bonus";

    public function execute()
    {
        if(!$this->isWebUser()) return "User can not be authenticated with webby.";

        preg_match("/^(\d+)$/", $this->text, $provided_tick);

        if($provided_tick) {
            $tick = intval($this->text);
            if($tick < 1) {
                return "Please use anything between 1 and 1177";
            }
            if($tick > 1177) {
                return "Please use anything between 1 and 1177";
            }
        }
        else {
            $currentTick = Tick::orderBy('tick', 'DESC')->first();
            $tick = $currentTick->tick;
            if(!$currentTick) return "The game hasn't even fucking started yet!";
        }

        $resource_bonus = round(10000 + ($tick * 4800));
        $asteroid_bonus = round(6 + ($tick * 0.15));
        $research_bonus = round(4000 + ($tick * 24));
        $construction_bonus = round(2000 + ($tick * 18));
        $research_bonus_5percent = round((4000 + ($tick * 24)) * 1.05);
        $construction_bonus_5percent = round((2000 + ($tick * 18)) * 1.05);

        return "Upgrade Bonus at tick " . $tick . "\n\nResource: " . number_format($resource_bonus) . " of each resource,  OR\nAsteroid: " . number_format($asteroid_bonus) . " of each asteroid\n\nAND\n\nResearch Points: " . number_format($research_bonus) . "-" . number_format($research_bonus_5percent) . " research points,  OR\nConstruction Points: " . number_format($construction_bonus) . "-" . number_format($construction_bonus_5percent) . " construction units";  
    }
}