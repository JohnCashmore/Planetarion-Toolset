<?php
namespace App\Telegram\Custom\Scans;

use App\Telegram\Custom\BaseCommand;
use App\User;
use App;
use DB;

class FundDestroyerCommand extends BaseCommand
{
    protected $command = "!funddestroyer";

    public function execute()
    {
	if(!$this->isWebUser()) return "User can not be authenticated with webby.";

        $requests = DB::table('scan_requests')->select(DB::raw('distinct user_id, count(*) as requests'))->groupBy('user_id')->orderBy('requests', 'DESC')->take(10)->get();

        $response = "Top 10 Scan Requesters:\n";
        ForEach($requests as $requester) {
            $name = User::where('id', $requester->user_id)->get()->pluck('name');
	    $member = isset($name[0]) ? $name[0] : "Deleted?";
            $response .= $member . " - " . $requester->requests . " requests.\n";
        }
        return $response;

    }
}