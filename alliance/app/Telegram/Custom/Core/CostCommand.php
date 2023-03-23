<?php
namespace App\Telegram\Custom\Core;

use App\Telegram\Custom\BaseCommand;
use App\Attack;
use App\Services\Misc\Cost;
use App;

class CostCommand extends BaseCommand
{
    protected $command = "!cost";

    public function execute()
    {
		if(!$this->isWebUser()) return "User can not be authenticated with webby.";
		
        $cost = App::make(Cost::class);

        $string = explode(" ", $this->text);
        if (count($string) < 2) return "Usage: !cost <amount> <ship>";

        return $cost->setShip($string[1])
                ->setAmount($string[0])
                ->execute();
    }
}