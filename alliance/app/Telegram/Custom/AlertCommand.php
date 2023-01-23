<?php
namespace App\Telegram\Custom;

use App\Telegram\Custom\BaseCommand;
use App\Telegram\Custom\SmsCommand;
use App\Attack;
use App;
use App\Tick;
use App\User;
use App\Planet;
use App\Services\CreateScanRequest;
use infobip\api\client\SendMultipleTextualSmsAdvanced;
use infobip\api\configuration\BasicAuthConfiguration;
use infobip\api\model\Destination;
use infobip\api\model\sms\mt\send\Message;
use infobip\api\model\sms\mt\send\textual\SMSAdvancedTextualRequest;

class AlertCommand extends BaseCommand
{
	protected $command = "!alert";
 
	public function execute()
	{
		if(!isset($this->text)) return "usage: !alert <message>";

		if(!$this->isWebUser()) return "Error: you are not registered with the tools. Please do !setnick <your_username>";
		
		<?php 
		$postDataJson = json_encode($postData);
		$itterations = 0;
		
		$users = User::where('name', 'LIKE', '%')->get();
		foreach ($users as $user):
			
			if ($user->phone):
				$cmd = new \App\Telegram\Custom\SmsCommand();
				$cmd->text = $user->name . ' ' . $this->text;
				$cmd->execute();
			endif;
		
		endforeach;


		return 'Message sent to ' . $itterations . ' contacts';
	}
}
