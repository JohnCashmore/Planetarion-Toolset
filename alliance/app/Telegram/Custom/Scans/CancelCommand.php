<?php
namespace App\Telegram\Custom\Scans;

use App\Telegram\Custom\BaseCommand;
use Longman\TelegramBot\Request;
use App\User;
use App\Planet;
use App\ScanRequest;
use App\Setting;
use App;
use DB;

class CancelCommand extends BaseCommand
{
    protected $command = "!cancel";

    public function execute()
    {
    
    if(!$this->isWebUser()) return "User can not be authenticated with webby.";

	$params   = explode(" ", $this->text);
	$req_id   = $params[0];
	$reason   = substr($this->text, (strlen($req_id) + 1));
	$comment  = isset($params[1]) ? $reason : "None";

	$user = User::where('tg_username', $this->userId)->first();
    $chatId = Setting::where('name', 'tg_scans_channel')->first();

    if($this->chatId == $chatId->value) {
    	$requests = DB::table('scan_requests')->whereNull('scan_id')->orderBy('created_at', 'desc')->get()->pluck('id');
	}
	else {
	    $requests = DB::table('scan_requests')->where('user_id', $user->id)->whereNull('scan_id')->orderBy('created_at', 'desc')->get()->pluck('id');
	}

	if($requests == "[]") {
	    return "There are no open requests to cancel or it's not your request!";
	}

	if(in_array($req_id, json_decode($requests, true))) {
	    $requester_id  	= DB::table('scan_requests')->where('id', $req_id)->get()->pluck('user_id');
	    $scantype 	   	= DB::table('scan_requests')->where('id', $req_id)->get()->pluck('scan_type');
	    $planet_id 	   	= DB::table('scan_requests')->where('id', $req_id)->get()->pluck('planet_id');
	    $planet	   		= Planet::where('id', $planet_id[0])->first();
	    $requester     	= User::where('id', $requester_id[0])->get()->pluck('tg_username');
        
        DB::table('scan_requests')->where('id', $req_id)->delete();

	    $message = "Scan Request " . $req_id . " (" . strtoupper($scantype[0]) . "/" . $planet->x . ":" . $planet->y . ":" . $planet->z . ") has been deleted!";
            Request::sendMessage([
            	'chat_id' => $this->chatId,
            	'text'    => $message,
	    ]);

	    $message = "Scan Request " . $req_id . " (" . strtoupper($scantype[0]) . "/" . $planet->x . ":" . $planet->y . ":" . $planet->z . ") has been deleted by " . $user->name . "\nComment: " . $comment;
        Request::sendMessage([
                'chat_id' => $requester[0],
                'text'    => $message,
	    ]);

	    if($this->chatId != $chatId->value) {
	        $message = "Scan Request " . $req_id . " (" . strtoupper($scantype[0]) . "/" . $planet->x . ":" . $planet->y . ":" . $planet->z . ") has been deleted!";
            Request::sendMessage([
            	    'chat_id' => $chatId->value,
            	    'text'    => $message,
	        ]);
	    }
	}
	else {
	    return "That's not an open request id or not your request!";
	}

	return "Thank fuck that's been sorted.";
    }
}