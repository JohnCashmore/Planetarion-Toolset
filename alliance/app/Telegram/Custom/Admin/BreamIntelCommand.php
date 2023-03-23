<?php
namespace App\Telegram\Custom\Admin;

use Longman\TelegramBot\Request;
use App\Telegram\Custom\BaseCommand;
use App\User;
use DB;

class BreamIntelCommand extends BaseCommand
{
    protected $command = "!bream";


  public function execute()
    {
        if(!$this->isWebUser()) return "User can not be authenticated with webby.";
        if(!$this->isChannelAllowed()) return "You can only use this command in the channel linked to webby.";

        $user = User::where('tg_username', $this->userId)->first();

        if($user->role_id <> 1) {
            return "You're not an admin....";
        }


		$link = "http://dummschueler.de/dummy.php?ally=" . $this->text;;
		
		try {
            $html = file_get_contents($link);

            } catch(\Exception $e) {
            $response = "Couldn't read url!";
	    	return $response;
        }

		$lines = explode("\n", $html);
		
		return $lines[0];
		
    }

//        return "length: " . strlen($html);
		


/*
    public function execute()
    {
        if(!$this->isWebUser()) return "User can not be authenticated with webby.";
        if(!$this->isChannelAllowed()) return "You can only use this command in the channel linked to webby.";

        $user = User::where('tg_username', $this->userId)->first();

        if($user->role_id <> 1) {
            return "You're not an admin....";
        }

		$link = "http://breampatools.ddns.net/PA/alliance_lookup.php?alli=" . $this->text;

		try {
            $html = file_get_contents($link);
        } catch(\Exception $e) {
            $response = "Couldn't read url!";
	    	return $response;
        }


        return "length: " . strlen($html);
		$lines = explode("\n", $html);

		
		return $lines[101] .  $lines[102] . "";
		
		## this seeems to be debug info.. wont continue parsing
		## return count($lines);
        
		ForEach($lines as $line) {		
		  if(strpos($line, '</form>')) {
				$tbody = explode("<tbody><tr>", $line);
			}
		}


		$trs = explode("<tr><td style=\"text-align:center;\">", $tbody[1]);

		$response = "";
		ForEach($trs as $tr) {
			preg_match('/planet_lookup\.php\?coords=(\d*:\d*:\d*)/', $tr, $match);
			$response .= $match[1] . ", ";
		}

		return substr($response, 0, -2);
    }
    
    */
}