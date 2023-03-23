<?php
namespace App\Telegram\Custom;

use App\Telegram\Custom\BaseCommand;
use App;
use App\User;
use Carbon\Carbon;
use DB;

class LocaltimeCommand extends BaseCommand
{
    protected $command = "!localtime";

    public function execute()
    {
        if(!$this->isWebUser()) return "User can not be authenticated with webby.";
        if(!$this->isChannelAllowed()) return "You can not use that command in this channel";
        
        $member = ltrim($this->text, '@');
        if (!$member) { 
            return "Usage: !localtime @telegram_username|webby_nickname";   
        } 

		$tgdb = DB::table('user')->where('username', $member)->first();
		if(!isset($tgdb)) {
			$user = User::where('name', $member)->first();
			if(!isset($user->name)) {
				$tgdb = DB::table('user')->where('first_name', 'like', '%' . $member . '%')->first();
				if(!isset($tgdb)) {
					return "No user found with this @telegram_username, webby_username or Telegram name: " . $member;
				}
				else {
					$user = User::where('tg_username', $tgdb->id)->first();
				}
			}
		}
		else {
			$user = User::where('tg_username', $tgdb->id)->first();
		}

		if(isset($user->timezone)) {
			$currentTime = Carbon::parse(Carbon::now($user->timezone));
			return sprintf("Local time for user %s is: %s", $user->name, $currentTime);
		} else {
			return sprintf("User %s has no time offset recorded in webby!", $user->name);
		}
	}
}