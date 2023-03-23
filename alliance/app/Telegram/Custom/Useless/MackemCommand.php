<?php
namespace App\Telegram\Custom\Useless;

use App\Telegram\Custom\BaseCommand;
use App\Attack;
use App\Services\Misc\Eff;
use App;

class MackemCommand extends BaseCommand
{
    protected $command = "!mackem";

    public function execute()
    {
        return "Galla is the VGN Mackem Bastard";
    }
}
