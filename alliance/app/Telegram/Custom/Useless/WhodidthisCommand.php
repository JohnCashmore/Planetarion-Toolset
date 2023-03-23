<?php
namespace App\Telegram\Custom\Useless;

use App\Telegram\Custom\BaseCommand;
use App\Attack;
use App\Services\Misc\Eff;
use App;

class WhodidthisCommand extends BaseCommand
{
    protected $command = "!whodidthis";

    public function execute()
    {
        return "The 2021 version of the webby and the swearing cuntbot were brought to you by Venox, Nitin '/spits', Sven, Macen, Geriatric, ChiefChronoX, Chris W and Johnny Aywah";
    }
}
     