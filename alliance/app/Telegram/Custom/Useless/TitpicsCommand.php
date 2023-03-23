<?php
namespace App\Telegram\Custom\Useless;

use App\Telegram\Custom\BaseCommand;
use App\Attack;
use App\Services\Misc\Eff;
use App;

class TitpicsCommand extends BaseCommand
{
    protected $command = "!titpics";

    public function execute()
    {
        return "Fuck off to Pornhub you dirty bastard! or try saying !boobs";
    }
}
