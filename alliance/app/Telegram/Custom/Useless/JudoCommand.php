<?php
namespace App\Telegram\Custom\Useless;

use App\Telegram\Custom\BaseCommand;
use App\Attack;
use App\Services\Misc\Eff;
use App;

class JudoCommand extends BaseCommand
{
    protected $command = "!judo";

    public function execute()
    {
        return "<a href='https://vgnpa.uk/images/judo.jpg'>JUDO!</a>";
    }
}
