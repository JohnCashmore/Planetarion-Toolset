<?php
namespace App\Telegram\Custom\Useless;

use App\Telegram\Custom\BaseCommand;
use App\Attack;
use App\Services\Misc\Eff;
use App;

class TekCommand extends BaseCommand
{
    protected $command = "!tek";

    public function execute()
    {
        return "<a href='https://vgnpa.uk/images/tek.jpg'>Officer Dibble</a>";
    }
}
