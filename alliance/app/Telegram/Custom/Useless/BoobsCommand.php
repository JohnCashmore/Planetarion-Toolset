<?php
namespace App\Telegram\Custom\Useless;

use App\Telegram\Custom\BaseCommand;
use App;
use App\User;

class BoobsCommand extends BaseCommand
{
    protected $command = "!boobs";

    public function execute()
    {
        if(!$this->isWebUser()) return "User can not be authenticated with webby.";

        $url = 'https://vgnpa.uk/images/roulette/' . rand(1,200) . '.jpg';
//	if($this->chatId == "-1001409429170") {
//	   return "Go wank in private, DM me instead ;-)";
//	}
//	else {
           return $url;
//	}
    }
}