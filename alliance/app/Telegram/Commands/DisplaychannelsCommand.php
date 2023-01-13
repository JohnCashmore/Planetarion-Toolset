<?php
namespace Longman\TelegramBot\Commands\SystemCommands;

use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Request;
use App\Setting;
use App;
use Config;
use App\Telegram\CommandTrait;

class DisplaychannelsCommand extends SystemCommand {
		protected $name = 'Displaychannels';
		protected $usage = '/displaychannels';

		public function execute()
		{

				if(!$this->isAdmin()) return Request::sendMessage(['chat_id' => $chatId, 'text' => "This command can only be used by admins."]);

				if($tgChannels = Setting::where('name', 'tg_channels')->first()->value) {
						$tgChannels = unserialize($tgChannels);
				} else {
						$tgChannels = [];
				}

				return Request::sendMessage($tgChannels);
		}

		private function isAdmin()
		{
				$admin = Config::get('phptelegrambot.admins')[0];
				$message = $this->getMessage();
				$chatId = $message->getChat()->getId();

				$userId  = $message->from['id'];

				if($admin == $userId) return true;

				return false;
		}
}