<?php
namespace App\Telegram\Custom\Useless;

use App\Telegram\Custom\BaseCommand;
use App\Services\Misc\Poster;
use App\Attack;
use App;

class PosterCommand extends BaseCommand
{
    protected $command = "!poster";

    public function execute()
    {
        $url = 'https://vgnpa.uk/images/poster/' . rand(1,45) . '.jpg';
        return $url;
    }
}