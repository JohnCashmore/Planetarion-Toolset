<?php
namespace App\Telegram\Custom\Useless;

use Longman\TelegramBot\Request;
use App\Telegram\Custom\BaseCommand;
use App;

class GeriatricCommand extends BaseCommand
{
    protected $command = "!geriatric";

    public function execute()
    {
        return "<a href='https://vgnpa.uk/images/geriatric.jpg'>Geriatric</a>";
    }
}
