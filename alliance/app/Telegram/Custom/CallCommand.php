<?php
namespace App\Telegram\Custom;

use App\Telegram\Custom\BaseCommand;
use App\Planet;
use App\User;

use DB;
use infobip\api\client\SendMultipleTextualSmsAdvanced;
use infobip\api\configuration\BasicAuthConfiguration;
use infobip\api\model\Destination;
use infobip\api\model\sms\mt\send\Message;
use infobip\api\model\sms\mt\send\textual\SMSAdvancedTextualRequest;

class CallCommand extends BaseCommand
{
	protected $command = "!call";

	public function execute()
	{
		// validation
		 if(!$this->text || strpos($this->text, ' ')):
			 return 'usage: !call <member>';
		 endif;
		 
		// place the call
		$transit = new \App\Services\Transit();
		return $transit->call(addslashes($this->text));

	}
}