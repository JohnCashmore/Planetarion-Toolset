<?php
namespace App\Telegram\Custom;

use App\Telegram\Custom\BaseCommand;
use App\User;

class SetnickCommand extends BaseCommand
{
	protected $command = "!setnick";

	public function execute()
	{
		if(!$this->isChannelAllowed()) return "You can only use this command in the channel linked to tools.";

		if(!$this->text) return "usage: !setnick <nick>";
		if(!$user = User::where('name', $this->text)->first()) return "Error: username not found. Please make sure you are enabled in the tools.";
		if($user->tg_username) return "That web user has already been linked to a TG user.";
		
		$user->tg_username = $this->message->from['id'];
		$user->save();

		return "Successfully linked Telegram user to tools user! Welcome!";
	}
}