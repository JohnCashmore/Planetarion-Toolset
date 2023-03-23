<?php
namespace App\Telegram\Custom;

use App\Telegram\Custom\BaseCommand;
use App;

class TickPlanCommand extends BaseCommand
{
    protected $command = "!tickplan";

    public function execute()
    {
        if(!$this->isWebUser()) return "User can not be authenticated with webby.";
        return "<a href='https://docs.google.com/spreadsheets/d/1XjVHx-GQargwPCtE0p2ObuaP7t_hXkiK/edit#gid=2692987'>R95 - Cores Rush Tick Plan 5 races by Mek</a>";
    }
}