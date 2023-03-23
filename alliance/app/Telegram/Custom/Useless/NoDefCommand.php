<?php
namespace App\Telegram\Custom\Useless;

use App\Telegram\Custom\BaseCommand;
use App;

class NoDefCommand extends BaseCommand
{
    protected $command = "!nodef";

    public function execute()
    {
        if(!$this->isWebUser()) return "User can not be authenticated with webby.";
        return "TeK!";
    }
}