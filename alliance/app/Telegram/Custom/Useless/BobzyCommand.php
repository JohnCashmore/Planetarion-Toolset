<?php
namespace App\Telegram\Custom\Useless;

use App\Telegram\Custom\BaseCommand;
use App\Attack;
use App\Services\Misc\Eff;
use App;

class BobzyCommand extends BaseCommand
{
    protected $command = "!bobzy";

    public function execute()
    {
        return "<a href='https://vgnpa.uk/images/bobzy.jpg'>Bobzy</a>";
    }
}