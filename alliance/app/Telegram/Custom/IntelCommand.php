<?php
namespace App\Telegram\Custom;

use App\Telegram\Custom\BaseCommand;
use App\Planet;
use App\Galaxy;

class IntelCommand extends BaseCommand
{
	protected $command = "!intel";

	public function execute()
	{
		if(!$this->isWebUser()) return "User can not be authenticated with webby.";
		
		// grab input
		$string = explode(' ', $this->text);
		
		// process data
		preg_match("/^(\d+)[.: ](\d+)[.: ](\d+)$/", $string[0], $planet);
		preg_match("/^(\d+)[.: ](\d+)$/", $string[0], $galaxy);

		$psearch = ($planet) ? $planet : false;

		if($psearch) {
			$x = $psearch[1];
			$y = $psearch[2];
			$z = $psearch[3];

			$planet = Planet::with('alliance')->where([
			  'x' => $x,
			  'y' => $y,
			  'z' => $z
			])->first();
			
			if (isset($string[1])):
				$planet->nick = $string[1];
				$planet->save();
				$alliance = ($planet->alliance) ? $planet->alliance->name : "";
				
				return sprintf("Information updated for %d:%d:%d: nick=%s alliance=%s", $x, $y, $z, $planet->nick, $alliance);
			endif;
			
			if($planet) {
				$alliance = ($planet->alliance) ? $planet->alliance->name : "";
				return sprintf("Information stored for %d:%d:%d: nick=%s alliance=%s", $x, $y, $z, $planet->nick, $alliance);
			} else {
				return sprintf("No planet found at %d:%d:%d", $x, $y, $z);
			}
		}

		$gsearch = ($galaxy) ? $galaxy : false;

		// gal search
		if($gsearch) {
			$x = $gsearch[1];
			$y = $gsearch[2];

			$galaxy = Galaxy::with([
				'planets' => function($q) {
					$q->with('alliance')->select(['z', 'alliance_id', 'nick', 'galaxy_id']);
				}
			])->where([
			  'x' => $x,
			  'y' => $y
			])->first();

			$planets = [];

			foreach($galaxy->planets as $planet) {
				$plan = "#" . $planet->z . "-" . $planet->nick;
				if(isset($planet->alliance->nickname)) {
					$plan .= "[" . $planet->alliance->nickname . "]";
				}
				$planets[] = $plan;
			}

			if($galaxy) {
				return sprintf("%s [%d:%d]: %s", $galaxy->name, $x, $y, implode(" ", $planets));
			} else {
				return sprintf("No galaxy found at %d:%d", $x, $y);
			}
		}

		return "usage: !intel x:y:z";
	}
}