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

class SmsCommand extends BaseCommand
{
	protected $command = "!sms";

	public function execute()
	{
		// validation
		 if(!$this->text):
			 return 'usage: !sms <member> <message>';
		 endif;
		 
		 $username = addslashes(substr($this->text, 0, strpos($this->text, ' ')));
		 $message = addslashes(substr($this->text, strpos($this->text, ' ')));
		 
		 if (strlen($username) < 1)
			 echo 'Please provide a username';
		 
		 if (strlen($message) < 1)
			 echo 'No message given';
		 
		 $transit = new \App\Services\Transit();
		 return $transit->sms($username, trim($message));
		
	}
}