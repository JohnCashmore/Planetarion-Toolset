<?php
namespace App\Telegram\Custom;

use App\Telegram\Custom\BaseCommand;
use App\User;
use App;
use DB;

class MaydayCommand extends BaseCommand
{
    protected $command = "!mayday";

    public function execute()
    {
        if(!$this->isChannelAllowed()) return "You can not use that command in this channel";

        $user = User::where('tg_username', $this->userId)->first();  
        if($user->name == "iBorg") { return "Nope!"; }

	$params = explode(" ", $this->text);
	$race = strtolower($params[0]);
	$valid_races = ['ter', 'cat', 'xan', 'zik', 'etd', 'all'];

	if(!$race) return "Usage: !mayday <all|ter|xan|cat|zik|etd>";

	if(!in_array($race, $valid_races)) {
	    return "Usage: !mayday <all|ter|xan|cat|zik|etd>";
	}

	if($race == 'all') {
            $users = User::orderBy('name', 'ASC')->where([
                'is_enabled' => 1
            ])->get();
	}
	else {
            $users = User::with('planet')->orderBy('name', 'ASC')->where([
                'is_enabled' => 1
            ])->get();
	}

        if(count($users)){
            $members = [];

            foreach($users as $user) {

		if($race == 'all') {
               	    if(env('PHP_TELEGRAM_BOT_API_KEY')) {
                	$user->tg_user = "";
                	if($user->tg_username) {
                	    $user->tg_user = DB::table('user')->where('id', $user->tg_username)->first();
			    if(!$user->tg_user->username == "") {
			    	$members[] = $user->tg_user->username;
				continue;
			    }
                	}
                    }
		}

		if($user->planet) {
		    if(ucwords($race) == $user->planet->race) {
                	if(env('PHP_TELEGRAM_BOT_API_KEY')) {
                    	    $user->tg_user = "";
                    	    if($user->tg_username) {
                    	        $user->tg_user = DB::table('user')->where('id', $user->tg_username)->first();
			        if(!$user->tg_user->username == "") {
			    	    $members[] = $user->tg_user->username;
			        }
                    	    }
                	}
		    }
		}

            }
	    
	    if(count($members)) {
                return sprintf("%s members: @%s", ucwords($race), implode(", @", $members));
	    }
	    else {
		return "No members with this race?";
	    }
        }

        return "There are no members";
    }
}