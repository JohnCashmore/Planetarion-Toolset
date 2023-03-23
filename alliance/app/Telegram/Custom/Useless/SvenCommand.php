<?php
namespace App\Telegram\Custom\Useless;

use App\Telegram\Custom\BaseCommand;
use App\Attack;
use App\Services\Misc\Eff;
use App;

class SvenCommand extends BaseCommand
{
    protected $command = "!sven";

    public function execute()
    {
        return "<a href='https://vgnpa.uk/images/sven_new.jpeg'>Sven</a>";
    }
}