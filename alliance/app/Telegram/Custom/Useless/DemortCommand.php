<?php
namespace App\Telegram\Custom\Useless;

use App\Telegram\Custom\BaseCommand;
use App\Attack;
use App\Services\Misc\Eff;
use App;

class DemortCommand extends BaseCommand
{
    protected $command = "!demort";

    public function execute()
    {
        return "<a href='https://vgnpa.uk/images/demort.jpg'>Demort</a>";
    }
}
