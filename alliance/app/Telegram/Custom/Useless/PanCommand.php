<?php
namespace App\Telegram\Custom\Useless;

use Longman\TelegramBot\Request;
use App\Telegram\Custom\BaseCommand;
use App;

class PanCommand extends BaseCommand
{
    protected $command = "!pan";

    public function execute()
    {
        return "<a href='https://vgnpa.uk/images/pan.jpg'>Pan</a>";
    }
}
