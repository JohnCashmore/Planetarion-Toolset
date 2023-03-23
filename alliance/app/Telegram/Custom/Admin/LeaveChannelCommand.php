<?php
namespace App\Telegram\Custom\Admin;

use App\Telegram\Custom\BaseCommand;
use Longman\TelegramBot\Request;
use App\User;
use App;
use DB;

class LeaveChannelCommand extends BaseCommand
{
    protected $command = "!leave";

    public function execute()
    {
        if(!$this->isWebUser()) return "User can not be authenticated with webby.";
        if(!$this->isChannelAllowed()) return "You can only use this command in the channel linked to webby.";

        $user = User::where('tg_username', $this->userId)->first();

        if($user->role_id <> 1) {
            return "You're not an admin....";
        }

	if(!$this->text) {
	    return "Provide a chat_id";
	}

        $name = DB::table('chat')->where('id', $this->text)->get()->pluck('title');

        if($name == "[]") {
	    return "I don't know that chat_id, check my !activechannels";
	}

        $message = "Trying to leave the following chat: " . $name[0] . " (" . $this->text . ")";
        Request::sendMessage([
            'chat_id' => $this->chatId,
            'text'    => ucfirst($message),
        ]);
    
        return Request::leaveChat(['chat_id' => $this->text]);
    }
}