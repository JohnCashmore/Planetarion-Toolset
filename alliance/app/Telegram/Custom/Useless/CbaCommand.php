<?php
namespace App\Telegram\Custom\Useless;

use App\Telegram\Custom\BaseCommand;
use App\Attack;
use App\Services\Misc\Eff;
use App;

class CbaCommand extends BaseCommand
{
    protected $command = "!cba";

    public function execute()
    {
        return "https://vgnpa.uk/images/cba.jpg";
    }
}