<?php
namespace App\Telegram\Custom;

use App\Telegram\Custom\BaseCommand;
use App\User;
use App;

class NotificationsCommand extends BaseCommand
{
    protected $command = "!notifications";

    public function execute()
    {
        if(!$this->isWebUser()) return "User can not be authenticated with webby.";

        $user = User::where('tg_username', $this->userId)->first();

        $email = "vgnpa.notifications+" . $user->id . "@gmail.com";
        $link = "https://game.planetarion.com/preferences.pl?#tab6";

        return "Below you will find your unique VGN notification email address that you can setup in Planetarion on the <a href='" . $link . "'>Set Notifications</a> page.\n\n" . $email . "";
    }
}