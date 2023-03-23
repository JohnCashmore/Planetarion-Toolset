<?php
namespace App\Telegram\Custom\Useless;

use App\Telegram\Custom\BaseCommand;
use App\Attack;
use App\Services\Misc\Eff;
use App;

class JokeCommand extends BaseCommand
{
    protected $command = "!joke";

    public function execute()
    {
        return "Sunderland Fucking Football Club!";
    }
}
