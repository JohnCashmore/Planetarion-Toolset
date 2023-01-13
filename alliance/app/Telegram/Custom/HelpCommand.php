<?php
namespace App\Telegram\Custom;

use App\Telegram\Custom\BaseCommand;
use App\Attack;
use App\Services\Misc\Eff;
use App;

class HelpCommand extends BaseCommand
{
	protected $command = "!help";

	public function execute()
	{
		$commands = array(
			'tools' => 'Website URL',
			'tick' => 'Display current tick or information for tick - Usage: !tick [tick]',
			'cookie' => 'Give user a virtual cookie - Usage: !cookie <nick> <reason>',
			'setnick' => 'Set your nick at round start - Usage: !setnick <nick>',
			'setplanet' => 'Set your planet at round start - Usage: !setplanet <x:y:z>',
			
			'attacks' => 'List open attacks',
			'claimed' => 'List claimed targets from open attacks',
			'book' => 'Book a target in an open attack, use !free to see available targets. - Usage: !book <x:y:z> <landing tick>',
			'drop' => 'Drop a target from attack - Usage: !drop <x:y:z> <landing tick>',
			'free' => 'List all available targets in attack',
			'launch' => 'Retrieve all claimed attack targets',
			
			'call' => 'Place a call to <nick> - Usage: !call <nick>',
			'sms' => 'Send an SMS to <nick> - Usage: !sms <nick> <message>',
			
			
			'maxcap' => 'Find max potential roid gain for <target> after <ticks of roiding> - Usage: !maxcap <x:y:z> [ticks]',
			'eff' => 'Effect of ship on ships it targets - Usage:  !eff <amount> <ship> [tier]',
			'stop' => 'Calculate ships required to stop ship with amount - Usage: !stop <ship> <amount>',
			'afford' => 'Work out potential for ships to be built for a given planet - Usage: !afford <coords> <ship>',
			'roidcost' => 'Cost of roids given based on supplied information - Usage: !roidcost <roids> <value loss> <mining bonus>',
			'ship' => 'Retrieve stats for <ship> - Usage: !ship <ship name>',
			
			
			
			'last24' => 'Fetch stats for last 24 ticks - Usage: !last24 [nick|alliance]',
			'top5' => 'Retrieve top 5 players with option to specify an alliance - Usage: !top5 [alliance]',
			'lookup' => 'Fetch your planets details - Usage: !lookup',
			'epenis' => 'Last 24 hours score change - Usage: !epenis [nick]',
			'galpenis' => 'Last 24 hours galaxy score change - Usage: !galpenis [nick]',
			'apenis' => 'Last 24 hours alliance score change for Unicorns',
			'winners' => 'Top 5 players in alliance',
			'loosers' => 'Bottom 5 players in alliance',
			'intel' => 'Set or retrieve intel for planet or galaxy - Usage: !intel <x:y:z> [nick]',
			
			
			'latestscan' => 'Latest scan for given coords and type - Usage: !latestscan <x:y:z> <pdau>',
			'cost' => 'Retrieve cost of given ships - Usage: !cost <amount> <ship>',
			
			'req' => 'Request a scan - Usage: !req <x:y:z> <pduaj>',
			'reqs' => 'List all open requests - Usage: scan/officer channel only',
			'parse' => 'Parse given scan url - Usage: !parse <scan url>',
			'jpg' => 'Parse scan link and notify those with prelaunch on them - Usage: !jpg <group scan url>',
			'spam' => 'Spam intel for given alliance - Usage: !spam <alliance>',
			
			'whodidthis' => 'Random love for those that helped with this project',
			
		);
		
		$reply = '';
		foreach ($commands as $command => $description):
			$reply .=  '!' . $command . ': ' . $description . PHP_EOL;
		endforeach;
		
		return $reply;
	}
}