<?php
namespace App\Telegram\Custom\Core;

use App\Telegram\Custom\BaseCommand;
use App\Attack;
use App\Services\Misc\Stop;
use App;

class StopCommand extends BaseCommand
{
    protected $command = "!stop";

    public function execute()
    {
		if(!$this->isWebUser()) return "User can not be authenticated with webby.";
		
        $stop = App::make(Stop::class);

        $string = explode(" ", $this->text);

        if(!$string[0] || !$string[1]) return "usage: !stop <amount> <ship>";

        return $stop->setName($string[1])
                ->setAmount($string[0])
                ->execute();
    }
}