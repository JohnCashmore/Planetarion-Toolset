<?php
namespace App\Telegram\Custom;

use App\Telegram\Custom\BaseCommand;
use App;
use App\Tick;
use App\User;
use Carbon\Carbon;

class CookieCommand extends BaseCommand
{
	protected $command = "!cookie";

	public function execute()
	{
		$string = explode(" ", $this->text);

		if(!isset($string[0]) || strlen($string[0]) <= 0) return "usage: !cookie <nick> <reason>";
		
		// find the user
		$count = User::where('name', 'LIKE', '%' . addslashes($string[0]) . '%')->count();
		if ($count == 0)
			return 'Unable to find anyone by that name';
		$user = User::getUser(addslashes($string[0]));
		
		// give cookie
		$cookie = new \App\Services\Cookie();
		if (isset($string[1])):
			$message = substr($this->text, strpos($this->text, ' ') + 1);
			$insert = array(
				'user_id' => $user['id'],
				'message' => $message
			);
			$cookie->insert($insert);
		endif;
		
		// count cookies
		$countCookies = $cookie->where('user_id', $user['id'])->get();
		
		if (isset($string[1]))
			return sprintf('Gave cookie to %s with message "%s" (%s total cookies)', $user['name'], $message, count($countCookies));
		else 
			return sprintf('%s has %s total cookies', $user['name'], count($countCookies));
			
		
	}
}