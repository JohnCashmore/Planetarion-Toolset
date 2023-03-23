<?php
namespace App\Telegram\Custom\Core;

use App\Telegram\Custom\BaseCommand;
use App\User;
use App\Tick;
use App;

class WelcomeCommand extends BaseCommand
{
    protected $command = "!welcome";

    public function execute()
    {
        if(!$this->isChannelAllowed()) return "You can not use that command in this channel";

	$register = App::make('url')->to('/') . '/register';
	$profile = App::make('url')->to('/') . '/#/account';
	$currentTick = Tick::orderBy('tick', 'DESC')->first();

	$response = "Welcome to <strong>VGN</strong> dickhead! and may our Lord Rob help you because no cunt here will. Anyway, here are your 6 steps to success\n\n";
	$response .= "1. Create an account on our webby:\n<a href='" . $register . "'>Register VGN Account</a>\n\n";
	$response .= "2. Once activated by a HC, type the following here:\n!setnick your_fucking_nickname_on_webby\n\n";
	if(!isset($currentTick)) {
	    $response .= "3. It hasn't ticked yet, so you cannot set your planet but set later using:\n!setplanet X:Y:Z.\n\n";
	}
	else {
	    $response .= "3. Set your planet by typing the following here:\n!setplanet X:Y:Z\n\n";
	}
	$response .= "4. Set your timezone, phone and possible notes in your profile:\n<a href='" . $profile . "'>VGN Profile</a>\n\n";

	$response .= "5. Check if you have a Telegram username setup:\n<strong>iPhone</strong>\n- Go to Settings in Telegram and click on Edit (Profile, top right)\n- Click on Username and specify one if not set already.\n\n<strong>Android</strong>\n- Go to Settings in Telegram\n- Click on Username and specify one if not set already\n\n";

	$response .= "6. <strong>Set your notifications.</strong> First open a DM with me and type !notifications and I will tell you what to do next\n\n";
	
	$response .= "Lastly, <strong>DON'T BE A CUNT!</strong> because that job has already been taken\n\n";

	return $response;
    }
}