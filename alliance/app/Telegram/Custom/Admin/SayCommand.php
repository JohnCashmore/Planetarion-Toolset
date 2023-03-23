<?php
namespace App\Telegram\Custom\Admin;

use App\Telegram\Custom\BaseCommand;
use Longman\TelegramBot\Request;
use App\User;
use App;

class SayCommand extends BaseCommand
{
    protected $command = "!say";

    public function execute()
    {
        if(!$this->isWebUser()) return "User can not be authenticated with webby.";

        $user = User::where('tg_username', $this->userId)->first();

        if($user->role_id <> 1) {
            return "You're not an admin....";
        }

        // Member chat: -1001409429170
        Request::sendMessage([
            'chat_id' => '-1001409429170',
            'text'    => ucfirst($this->text),
        ]);

        Request::sendMessage([
            'chat_id' => $this->chatId,
            'text'    => 'Message sent to the Member Channel.',
        ]);

    }
}