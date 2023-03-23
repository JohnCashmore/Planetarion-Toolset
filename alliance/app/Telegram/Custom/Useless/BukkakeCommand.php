<?php
namespace App\Telegram\Custom\Useless;

use App\Telegram\Custom\BaseCommand;
use App\Alliance;
use App\Planet;
use App;

class BukkakeCommand extends BaseCommand
{
    protected $command = "!bukkake";

    public function execute()
    {
        if(!$this->isChannelAllowed()) return "You can not use that command in this channel, now fuck off";

        if($alliance = Alliance::where('name', 'like', '%' . $this->text . '%')->orWhere('nickname', $this->text)->first()) {
            $coords = [];
            $planets = Planet::where('alliance_id', $alliance->id)->get();
            foreach($planets as $planet) {
                $coords[] = $planet->x . ":" . $planet->y . ":" . $planet->z;
            }
            return sprintf("Aye! spray your love sauce on the following coords " . $alliance->name . ": " . implode(" ", $coords));
        } else {
            return "Check your fucking spelling dickhead.";
        }
    }
}