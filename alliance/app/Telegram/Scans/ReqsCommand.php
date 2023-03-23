<?php
namespace App\Telegram\Custom\Scans;

use App\Telegram\Custom\BaseCommand;
use App\Attack;
use App;
use App\ScanRequest;
use App\Setting;
use DB;
use Config;
use App\Tick;
use Longman\TelegramBot\Request;	

class ReqsCommand extends BaseCommand
{
    protected $command = "!reqs";

    public function execute()
    {
        $chatId = Setting::where('name', 'tg_scans_channel')->first();

        if($this->chatId != $chatId->value) return "You can only use that command in the designated scan channel.";

	$response = "Open Scan Requests:\n";
        $requests = DB::table('scan_requests')->whereNull('scan_id')->orderBy('created_at', 'desc')->get();

	if($requests == "[]") {
	    return "All Clear, Rob's blessing upon your amps.";
	}

	$scans = Config::get('scans');
	$currentTick = Tick::orderBy('tick', 'DESC')->first();

	$pscans = "";
	$dscans = "";
	$uscans = "";
	$nscans = "";
	$jscans = "";
	$ascans = "";
	$mscans = "";

        ForEach($requests as $request) {
	    $requester = DB::table('users')->where('id', $request->user_id)->get()->pluck('name');
	    $planet = DB::table('planets')->where('id', $request->planet_id)->get();
	    $type_id = $scans[$request->scan_type];
	    $link = 'https://game.planetarion.com/waves.pl?id=' . $type_id . '&x=' . $planet[0]->x . '&y=' . $planet[0]->y . '&z=' . $planet[0]->z;
	    $request_age = $currentTick->tick - $request->tick;
	    $response .= "[" . $request->id . "] [" . strtoupper($request->scan_type) . "] " . $planet[0]->x . ":" . $planet[0]->y . ":" . $planet[0]->z . " (D:" . $planet[0]->dists . ") - " . $requester[0] . " - Tick: " . $request->tick . " (Age:" . $request_age . ")\n" . $link . "\n\n";

	    if($request->scan_type == 'p') {
		$pscans .= $planet[0]->x . ":" . $planet[0]->y . ":" . $planet[0]->z . ", ";
	    }
	    elseif($request->scan_type == 'd') {
		$dscans .= $planet[0]->x . ":" . $planet[0]->y . ":" . $planet[0]->z . ", ";
	    }
	    elseif($request->scan_type == 'u') {
		$uscans .= $planet[0]->x . ":" . $planet[0]->y . ":" . $planet[0]->z . ", ";
	    }
	    elseif($request->scan_type == 'n') {
		$nscans .= $planet[0]->x . ":" . $planet[0]->y . ":" . $planet[0]->z . ", ";
	    }
	    elseif($request->scan_type == 'j') {
		$jscans .= $planet[0]->x . ":" . $planet[0]->y . ":" . $planet[0]->z . ", ";
	    }
	    elseif($request->scan_type == 'a') {
		$ascans .= $planet[0]->x . ":" . $planet[0]->y . ":" . $planet[0]->z . ", ";
	    }
		elseif($request->scan_type == 'm') {
		$mscans .= $planet[0]->x . ":" . $planet[0]->y . ":" . $planet[0]->z . ", ";
	    }
	}

	if($this->text == "bulk") {
	    if(strlen($pscans) > 1) {
        	Request::sendMessage([
            	    'chat_id' => $this->chatId,
            	    'text'    => 'Requested Planet scans:',
		]);

        	Request::sendMessage([
            	    'chat_id' => $this->chatId,
            	    'text'    => substr($pscans, 0, -2),
		]);
	    }

	    if(strlen($dscans) > 1) {
        	Request::sendMessage([
            	    'chat_id' => $this->chatId,
            	    'text'    => 'Requested Development scans:',
		]);

        	Request::sendMessage([
            	    'chat_id' => $this->chatId,
            	    'text'    => substr($dscans, 0, -2),
		]);
	    }

	    if(strlen($uscans) > 1) {
        	Request::sendMessage([
            	    'chat_id' => $this->chatId,
            	    'text'    => 'Requested Unit scans:',
		]);

        	Request::sendMessage([
            	    'chat_id' => $this->chatId,
            	    'text'    => substr($uscans, 0, -2),
		]);
	    }
	    
	    if(strlen($nscans) > 1) {
        	Request::sendMessage([
            	    'chat_id' => $this->chatId,
            	    'text'    => 'Requested News scans:',
		]);

        	Request::sendMessage([
            	    'chat_id' => $this->chatId,
            	    'text'    => substr($nscans, 0, -2),
		]);
	    }

	    if(strlen($jscans) > 1) {
        	Request::sendMessage([
            	    'chat_id' => $this->chatId,
            	    'text'    => 'Requested Jumpgate scans:',
		]);

        	Request::sendMessage([
            	    'chat_id' => $this->chatId,
            	    'text'    => substr($jscans, 0, -2),
		]);
	    }

	    if(strlen($ascans) > 1) {
        	Request::sendMessage([
            	    'chat_id' => $this->chatId,
            	    'text'    => 'Requested Advanced Unit scans:',
		]);

        	Request::sendMessage([
            	    'chat_id' => $this->chatId,
            	    'text'    => substr($ascans, 0, -2),
		]);
	    }
		
		if(strlen($mscans) > 1) {
        	Request::sendMessage([
            	    'chat_id' => $this->chatId,
            	    'text'    => 'Requested Military scans:',
		]);

        	Request::sendMessage([
            	    'chat_id' => $this->chatId,
            	    'text'    => substr($mscans, 0, -2),
		]);
	    }
	}
	else {
	    return $response;
	}
    }
}