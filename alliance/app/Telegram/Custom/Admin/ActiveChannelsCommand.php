<?php
namespace App\Telegram\Custom\Admin;

use App\Telegram\Custom\BaseCommand;
use Longman\TelegramBot\Request;
use App\User;
use App;
use DB;

class ActiveChannelsCommand extends BaseCommand
{
    protected $command = "!activechannels";

    public function execute()
    {
        if(!$this->isWebUser()) return "User can not be authenticated with webby.";
        if(!$this->isChannelAllowed()) return "You can only use this command in the channel linked to webby.";

        $user = User::where('tg_username', $this->userId)->first();

        if($user->role_id <> 1) {
            return "You're not an admin....";
        }

        $days           = date('Y-m-d H:i:s', strtotime('-7 days'));
        $channels       = DB::table('message')->select(DB::raw('distinct chat_id, MAX(date) as date'))->where('chat_id', '<', 0)->whereDate('date', '>', $days)->groupBy('chat_id')->orderBy('date', 'DESC')->get();
        
        $response       = "In the last 7 days I've seen activity in the following channels:\n";

        ForEach($channels as $channel) {
            $chat_id 	= $channel->chat_id;
            $name 	    = DB::table('chat')->where('id', $chat_id)->get()->pluck('title');
            $last_date 	= $channel->date;
            $response  .= "<strong>" . $name[0] . "</strong> (" . $chat_id . ")\n";
        }
        return $response;
    }
}