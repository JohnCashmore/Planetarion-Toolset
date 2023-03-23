<?php
namespace App\Telegram\Custom\Useless;

use App\Telegram\Custom\BaseCommand;
use App\Attack;
use App\Services\Misc\Eff;
use App;

class GallaCommand extends BaseCommand
{
    protected $command = "!galla";

    public function execute()
    {
        return "<a href='https://vgnpa.uk/images/galla.jpg'>Galla at a Sunderland match</a>";
    }
}
