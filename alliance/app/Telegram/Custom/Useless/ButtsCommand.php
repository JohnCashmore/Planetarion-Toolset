<?php
namespace App\Telegram\Custom\Useless;

use App\Telegram\Custom\BaseCommand;
use App;

class ButtsCommand extends BaseCommand
{
    protected $command = "!butts";

    public function execute()
    {
        if(!$this->isWebUser()) return "User can not be authenticated with webby.";

        $url = 'https://vgnpa.uk/images/butts/' . rand(1,25) . '.jpg';
        return $url;
    }
}