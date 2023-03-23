<?php
namespace App\Telegram\Custom\Useless;

use App\Telegram\Custom\BaseCommand;
use App;

class RobCommand extends BaseCommand
{
    protected $command = "!rob";

    public function execute()
    {
        return "Glory be unto the RobGod! the AllFather! the Creator! He who liberates us from the burden of free thought and the shackles of accountability! the only cunt here I actually like!\n\n Praise him! Praise him! Praise him!\n\n ROBGOD AKHBAAAAAAAAAAARRRRR!";
    }
}
