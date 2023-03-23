<?php
namespace App\Telegram\Custom\Useless;

use App\Telegram\Custom\BaseCommand;
use App\Attack;
use App\Services\Misc\Eff;
use App;

class AywahCommand extends BaseCommand
{
    protected $command = "!aywah";

    public function execute()
    {
        return "I'm sorry, but the number you have dialled is not connected. Please check this number before dialling again";
    }
}
     