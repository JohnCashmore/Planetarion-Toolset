<?php
namespace App\Telegram\Custom\Useless;

use App\Telegram\Custom\BaseCommand;
use App\Attack;
use App\Services\Misc\Eff;
use App;

class ChurchCommand extends BaseCommand
{
    protected $command = "!church";

    public function execute()
    {
        return " @Lonney123 @Demort5 @Bruxius @Tizza89 @acvxqs @JohnnyAywah @AVROB @Zurithil @Onim_pa @GoosePA @DrDubs @Cheedai @nicholaswillequet @Skhy85 @DoomDave @Judo3D @Pheonix65  Sons of the living God! Attend! There is Rob work to be done";
    }
}
