<?php
namespace App\Telegram\Custom\Core;

use App\Telegram\Custom\BaseCommand;
use App\Attack;
use App\Services\Misc\Eff;
use App;

class EffCommand extends BaseCommand
{
    protected $command = "!eff";

    public function execute()
    {
	if(!$this->isWebUser()) return "User can not be authenticated with webby.";
        if(!$this->hasTelegramUsername()) return "Cannot use this command without a Telegram username.\n\n<strong>iPhone</strong>\n- Go to Settings in Telegram and click on Edit (Profile, top right)\n- Click on Username and specify one if not set already.\n\n<strong>Android</strong>\n- Go to Settings in Telegram\n- Click on Username and specify one if not set already.";
		
        $eff = App::make(Eff::class);

        $string = explode(" ", $this->text);

        if(!isset($string[0]) || !isset($string[1])) return "usage: !eff <amount> <ship>";

        return $eff->setName($string[1])
                ->setAmount($string[0])
                ->execute();
    }
}