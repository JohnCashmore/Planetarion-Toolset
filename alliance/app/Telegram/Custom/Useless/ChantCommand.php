<?php
namespace App\Telegram\Custom\Useless;

use App\Telegram\Custom\BaseCommand;
use App;

class ChantCommand extends BaseCommand
{
    protected $command = "!chant";

    public function execute()
    {
        return "Domine Rob, Pater Rob, Parce nobis ab necessitate liberae cogitationis, libera nos ab omni reddendi ratione, et salva nos a machinis Nitinus.";
    }
}
