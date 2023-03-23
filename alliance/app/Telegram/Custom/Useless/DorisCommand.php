<?php
namespace App\Telegram\Custom\Useless;

use App\Telegram\Custom\BaseCommand;
use App\Attack;
use App\Services\Misc\Doris;
use App;

class DorisCommand extends BaseCommand
{
    protected $command = "!doris";

    public function execute()
    {
        if(!$this->isWebUser()) return "User can not be authenticated with webby.";

        return "https://vgnpa.uk/images/misc/doris.jpg";
    }
}