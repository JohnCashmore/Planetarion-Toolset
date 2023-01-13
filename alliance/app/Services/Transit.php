<?php

namespace App\Services;

use App\Telegram\Custom\BaseCommand;
use App\Planet;
use App\User;
use App\Schedule;
use DB;
use infobip\api\client\SendMultipleTextualSmsAdvanced;
use infobip\api\configuration\BasicAuthConfiguration;
use infobip\api\model\Destination;
use infobip\api\model\sms\mt\send\Message;
use infobip\api\model\sms\mt\send\textual\SMSAdvancedTextualRequest;
use Carbon\Carbon;

class Transit
{
	
	/**
	 * Send an SMS to member
	 *
	 * @return string
	 * @author Craig Fairhurst
	 */
	function sms($to, $message)
	{
		// sanitize data
		$username = addslashes($to);
		$message = addslashes($message);
		
		// find the user
		$count = User::where('name', 'LIKE', '%' . $username . '%')->count();
		if ($count == 0)
			return 'Unable to find anyone by that name';
		$user = User::getUser($username);
		$user['phone'] = preg_replace('/[^\d]+/', '', $user['phone']);
		
		// check if sms should be sent
		if (!$user['phone'])
			return 'User "' . $user['name']. '" does not have a phone number entered.';
		if ($this->isAllowed($user['name']) == false)
			return 'SMS disabled for "' . $user['name'] . '" for this tick';
		
		// send sms
		$from = env('APP_NAME');
		$messageId = time();
		$text = $message;
		$infobip['username'] = env('INFOBIP_USERNAME');
		$infobip['password'] = env('INFOBIP_PASSWORD');

		$postUrl = "https://" . env('INFOBIP_URL') . "/sms/2/text/advanced";

		// creating an object for sending SMS
		$destination = array(
			"messageId" => $messageId,
			"to" => $user['phone']
	   );

		$message = array(
			"from" => $from,
			"destinations" => array($destination),
			"text" => str_replace('\\', '', $text)
		);
		$postData = array("messages" => array($message));
		$postDataJson = json_encode($postData);

		$ch = curl_init();
		$header = array("Content-Type:application/json", "Accept:application/json");

		curl_setopt($ch, CURLOPT_URL, $postUrl);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($ch, CURLOPT_USERPWD, $infobip['username'] . ":" . $infobip['password']);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		curl_setopt($ch, CURLOPT_MAXREDIRS, 2);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postDataJson);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

		// response of the POST request
		$response = curl_exec($ch);
		$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		$responseBody = json_decode($response);
		curl_close($ch);
		
		
		if (strlen($user['timezone']) > 2):
			return sprintf('Sending message "%s" to "%s" at %s (%s)', $text, $user['name'], date('H:m:s', strtotime(Carbon::parse(Carbon::now($user['timezone'])))), Carbon::parse(Carbon::now($user['timezone']))->timezoneName);
		else:
			return sprintf('Sending message "%s" to "%s" at %s (%s)', $text, $user['name'], date('H:m:s', strtotime(Carbon::parse(Carbon::now()))), Carbon::parse(Carbon::now())->timezoneName);
		endif;
	}
	/**
	 * Place a call to user
	 *
	 * @return void
	 * @author Craig Fairhurst
	 */
	function call($to)
	{
		
		// validate input
		$username = addslashes($to);
		$count = User::where('name', 'LIKE', '%' . $username . '%')->count();
		if ($count == 0)
			return 'Unable to find anyone by that name';
		
		// fetch the user
		$user = User::getUser($username);
		$user['phone'] = preg_replace('/[^\d]+/', '', $user['phone']);
		
		// check if call should be made
		if (!$user['phone'])
			return 'User "' . $user['name']. '" does not have a phone number entered.';
		if (!$this->isAllowed($username))
			return 'Calls disabled for "' . $user['name'] . '" at nightime (' . Carbon::parse(Carbon::now($user['timezone']))->timezoneName . ')';
		
		// place call
		$from = env('APP_NAME');
		$messageId = time();
		$infobip['username'] = env('INFOBIP_USERNAME');
		$infobip['password'] = env('INFOBIP_PASSWORD');

		$postUrl = "https://" . env('INFOBIP_URL') . "/tts/3/single";
		
		$postData = array(
			"from" => $from,
			"to" => $user['phone'],
			"language" => 'en',
			"voice" => array(
				'name' => 'Joanna',
				'gender' => 'female'
			),
			"text" => 'You are needed - see Telegram'
		);
		$postDataJson = json_encode($postData);
		
		$ch = curl_init();
		$header = array("Content-Type:application/json", "Accept:application/json");

		curl_setopt($ch, CURLOPT_URL, $postUrl);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($ch, CURLOPT_USERPWD, env('INFOBIP_USERNAME') . ":" . env('INFOBIP_PASSWORD'));
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		curl_setopt($ch, CURLOPT_MAXREDIRS, 2);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postDataJson);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

		// response of the POST request
		$response = curl_exec($ch);
		$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		$responseBody = json_decode($response);
		curl_close($ch);
		
		if (strlen($user['timezone']) > 2):
			return sprintf('Call placed to "%s" at %s (%s)', $user['name'], date('H:m:s', strtotime(Carbon::parse(Carbon::now($user['timezone'])))), Carbon::parse(Carbon::now($user['timezone']))->timezoneName);
		else:
			return sprintf('Call placed to "%s" at %s (%s)', $user['name'], date('H:m:s', strtotime(Carbon::parse(Carbon::now()))), Carbon::parse(Carbon::now())->timezoneName);
		endif;
	}
	
	/**
	 * Check if user has enabled nightime calls
	 *
	 * @return void
	 * @author Craig Fairhurst
	 */
	function isAllowed($username)
	{
		
		// fetch the user
		$user = User::getUser($username);
			
		// check if its nightime
		if (strlen($user['timezone']) > 2):
			$time = Carbon::parse(Carbon::now($user['timezone'])->startOfHour());
			$day = strtolower(date('l', strtotime($time)));
			$hour = date('H', strtotime($time));
			$schedule = Schedule::where(['user_id' => $user['id'], 'datetime' => $day . '_' . $hour])->first();
		
			if (isset($schedule['datetime'])):
				return false;
			endif;
		else:
			return true;
		endif;
		
		// allow action
		return true;
	}

}
