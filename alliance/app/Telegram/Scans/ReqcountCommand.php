<?php
namespace App\Telegram\Custom\Scans;

use Longman\TelegramBot\Request;
use App\Telegram\Custom\BaseCommand;
use App\User;
use App;
use DB;

class ReqcountCommand extends BaseCommand
{
    protected $command = "!reqcount";

    public function execute()
    {
        if(!$this->isWebUser()) return "User can not be authenticated with webby.";

        $string = explode(" ", $this->text);
        if (strlen($string[0]) <> 1) return "Usage: !reqcount <p|d|u|n|i|j|a|m> - example: !reqcount m";

        if (str_contains('pdunijam', $string[0])) {
			$requests = DB::table('scan_requests')->select(DB::raw('distinct user_id, count(*) as requests'))->where('scan_type', '=', $string[0])->groupBy('user_id')->orderBy('requests', 'DESC')->take(10)->get();
		}

        $response = "Top 10 Scan Requesters: (" . $string[0] . ")\n";
        ForEach($requests as $requester) {
            $name = User::where('id', $requester->user_id)->get()->pluck('name');
	    $member = isset($name[0]) ? $name[0] : "Deleted?";
            $response .= $member . " - " . $requester->requests . " requests.\n";
        }
        return $response;
    }
}