<?php
namespace App\Telegram\Custom\Useless;

use App\Telegram\Custom\BaseCommand;
use App\Attack;
use App\Services\Misc\Eff;
use App;

class DoggCommand extends BaseCommand
{
    protected $command = "!dogg";

    public function execute()
    {
        return "<a href='https://vgnpa.uk/images/dogg.jpg'>Dogg</a>";
    }
}