<?php
namespace App\Telegram\Custom\Useless;

use Longman\TelegramBot\Request;
use App\Telegram\Custom\BaseCommand;
use App;

class GooseCommand extends BaseCommand
{
    protected $command = "!goose";

    public function execute()
    {
        return "<a href='https://vgnpa.uk/images/goose.jpg'>Goose</a>";
    }
}
