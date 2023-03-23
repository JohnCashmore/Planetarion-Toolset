<?php
namespace App\Telegram\Custom\Admin;

use Longman\TelegramBot\Request;
use App\Telegram\Custom\BaseCommand;
use App\User;
use DB;

class MembersCommand extends BaseCommand
{
    protected $command = "!members";

    public function execute()
    {
        if(!$this->isWebUser()) return "User can not be authenticated with webby.";

        $user = User::where('tg_username', $this->userId)->first();

        if($user->role_id <> 1) {
            return "You're not an admin....";
        }

		$tags = DB::table('tags')->orderBy('id', 'ASC')->get();
		$response = collect([]);

		ForEach($tags as $tag) {
			$members = DB::table('users')->orderBy('tag_id', 'ASC')->orderBy('role_id', 'ASC')->orderBy('name', 'ASC')->where(['is_enabled' => 1])->where(['tag_id' => $tag->id])->get();
			$count = DB::table('users')->where(['is_enabled' => 1])->where(['tag_id' => $tag->id])->count();
			$hc = DB::table('users')->where(['is_enabled' => 1])->where(['tag_id' => $tag->id])->where(['role_id' => 1])->count();
			$bc = DB::table('users')->where(['is_enabled' => 1])->where(['tag_id' => $tag->id])->where(['role_id' => 2])->count();
			$nm = DB::table('users')->where(['is_enabled' => 1])->where(['tag_id' => $tag->id])->where(['role_id' => 3])->count();
			$sc = DB::table('users')->where(['is_enabled' => 1])->where(['tag_id' => $tag->id])->where(['scanner' => 1])->count();

			$message = $tag->name . " (" . $count . ") - ADMIN: " . $hc . " BC: " . $bc . " MEMBER: " . $nm . " SCANNER: " . $sc;

			Request::sendMessage([
				'chat_id'    => $this->chatId,
				'parse_mode' => 'html',
				'text'       => $message,
			]);
		}
    }
}