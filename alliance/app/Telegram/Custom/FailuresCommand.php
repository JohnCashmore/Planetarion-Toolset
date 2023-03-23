<?php
namespace App\Telegram\Custom;

use App\Telegram\Custom\BaseCommand;
use App\User;
use DB;

class FailuresCommand extends BaseCommand
{
    protected $command = "!failures";

    public function execute()
    {
        if(!$this->isChannelAllowed()) return "You can not use that command in this channel";

        $users = User::orderBy('name', 'ASC')->where([
            'is_enabled' => 1
        ])->get();

        if(count($users)) {
            $notglinkedmembers = "";
			$notglinked = 0;
			$notgusernamemembers = "";
			$notgusername = 0;

            foreach($users as $user) {
				if(env('PHP_TELEGRAM_BOT_API_KEY')) {
					$user->tg_user = "";
					if($user->tg_username) {
						$user->tg_user = DB::table('user')->where('id', $user->tg_username)->first();
						if($user->tg_user->username == "") {
							$notgusernamemembers .= "<a href='tg://user?id=" . $user->tg_username . "'>" . $user->name . "</a>, ";
							$notgusername++;
						}
					}
					else {
						$notglinkedmembers .= "" . $user->name . ", ";
						$notglinked++;
					}
				}
			}

			if($notglinked == 0) {
				$notglinkedmembers = "None  ";
			}

			if($notgusername == 0) {
				$notgusernamemembers = "None  ";
			}
			
			return "No Telegram Username:\n" . substr($notgusernamemembers, 0, -2) . "\n\nNot Linked to Webby:\n" . substr($notglinkedmembers, 0, -2);
		}
    }
}