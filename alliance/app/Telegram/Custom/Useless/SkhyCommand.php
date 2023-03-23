<?php
namespace App\Telegram\Custom\Useless;

use Longman\TelegramBot\Request;
use App\Telegram\Custom\BaseCommand;
use App;

class SkhyCommand extends BaseCommand
{
    protected $command = "!skhy";

    public function execute()
    {
        return "<a href='https://vgnpa.uk/images/skhy.jpg'>Skhy</a>";
    }
}
