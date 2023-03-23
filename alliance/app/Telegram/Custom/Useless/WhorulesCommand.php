<?php
namespace App\Telegram\Custom\Useless;

use App\Telegram\Custom\BaseCommand;
use App\Attack;
use App\Services\Misc\Eff;
use App;

class WhorulesCommand extends BaseCommand
{
    protected $command = "!whorules";

    public function execute()
    {
        return "There is no God but Rob and Sven is his only messenger";
    }
}
