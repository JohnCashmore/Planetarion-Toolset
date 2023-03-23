<?php
namespace App\Telegram\Custom\Useless;

use App\Telegram\Custom\BaseCommand;
use App\Attack;
use App\Services\Misc\Eff;
use App;

class WessehCommand extends BaseCommand
{
    protected $command = "!wesseh";

    public function execute()
    {
        return "<a href='https://vgnpa.uk/images/wesseh.jpg'>Wesseh</a>";
    }
}