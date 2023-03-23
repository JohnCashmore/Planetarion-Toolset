<?php
namespace App\Telegram\Custom\Useless;

use App\Telegram\Custom\BaseCommand;
use App;
use App\User;

class PiesCommand extends BaseCommand
{
    protected $command = "!pies";

    public function execute()
    {
        if(!$this->isWebUser()) return "User can not be authenticated with webby.";
        if(!$this->hasTelegramUsername()) return "Cannot use this command without a Telegram username.\n\n<strong>iPhone</strong>\n- Go to Settings in Telegram and click on Edit (Profile, top right)\n- Click on Username and specify one if not set already.\n\n<strong>Android</strong>\n- Go to Settings in Telegram\n- Click on Username and specify one if not set already.";

        $url = 'https://vgnpa.uk/images/pies/' . rand(1,20) . '.jpg';
//	if($this->chatId == "-1001409429170") {
//	   return "Go wank in private, DM me instead ;-)";
//	}
//	else {
           return $url;
//	}
    }
}