<?php
namespace App\Telegram\Custom\Useless;

use Longman\TelegramBot\Request;
use App\Telegram\Custom\BaseCommand;
use App;

class iBorgCommand extends BaseCommand
{
    protected $command = "!iborg";

    public function execute()
    {
        return "All my homies hate iBorg.";
        //return "<a href='https://vgnpa.uk/images/iborg.jpg'>iBorg</a>";
    }
}