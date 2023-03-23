<?php
namespace App\Telegram\Custom\Attacks;

use App\Telegram\Custom\BaseCommand;
use App\Attack;
use App;

class AttacksCommand extends BaseCommand
{
    protected $command = "!attacks";

    public function execute()
    {
        if(!$this->isChannelAllowed()) return "You can not use that command in this channel";
        if(!$this->hasTelegramUsername()) return "Cannot use this command without a Telegram username.\n\n<strong>iPhone</strong>\n- Go to Settings in Telegram and click on Edit (Profile, top right)\n- Click on Username and specify one if not set already.\n\n<strong>Android</strong>\n- Go to Settings in Telegram\n- Click on Username and specify one if not set already.";

        $attacks = Attack::where([
            'is_closed' => 0,
            'is_opened' => 1
        ])->get();

        if(count($attacks)){
            $urls = [];
	    $attackinfo = "";

            foreach($attacks as $attack) {
                $url = App::make('url')->to('/') . '/#/attacks/' . $attack->attack_id;
		$attackinfo .= sprintf("<a href='%s'>Attack %s</a> - LT: %s - Notes: %s\n", $url, $attack->id, $attack->land_tick, $attack->notes);
            }

            return $attackinfo;
        }

        return "There are no open attacks, you will just have to crash and die alone";
    }
}