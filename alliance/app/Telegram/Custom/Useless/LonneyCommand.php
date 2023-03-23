<?php
namespace App\Telegram\Custom\Useless;

use App\Telegram\Custom\BaseCommand;
use App\Attack;
use App\Services\Misc\Eff;
use App;

class LonneyCommand extends BaseCommand
{
    protected $command = "!lonney";

    public function execute()
    {
        return "<a href='https://vgnpa.uk/images/lonney.jpg'>Lonney</a>";
    }
}
