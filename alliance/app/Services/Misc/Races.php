<?php

namespace App\Services\Misc;

use App\Ship;
use App\Services\Misc\Stats;

class Races
{

	public function execute($amount = '10000')
	{

		// setup variables
		$reply = '';
		$classes = array(
			'Fighter' => 'FI',
			'Corvette' => 'CO',
			'Frigate' => 'FR',
			'Destroyer' => 'DE',
			'Battleship' => 'BS',
			'Cruiser' => 'CR',
			'Roids' => 'RO',
			'Structure' => 'ST'
		);
		$races = array(
			'Terran' => 'Ter',
			'Cathaar' => 'Cat',
			'Xandathrii' => 'Xan',
			'Zikonian' => 'Zik',
			'Eitraides' => 'Etd'
		);
		$stats = new Stats();
		
		// process data
		$ships = Ship::get();
		foreach($ships as $id => $ship):
			
			// ignore astropods and structures
			if ($ship->t1 == 'Roids'):
				$roids[$ship->race][$ship->class] = true;
				continue;
			endif;
			if ($ship->t1 == 'Structure')
				continue;
			
			$orderedByRace[$ship->race][] = $ship;
			if (isset($ship->t1) && $ship->t1 != NULL)
				$t1[$classes[$ship->t1]][] = $ship;
			if (isset($ship->t2) && $ship->t2 != NULL)
				$t2[$classes[$ship->t2]][] = $ship;
			if (isset($ship->t3) && $ship->t3 != NULL)
				$t3[$classes[$ship->t3]][] = $ship;			
		endforeach;	
		
		$retVal = '';
		foreach ($orderedByRace as $race => $ships):
			foreach ($ships as $ship):
				$retVal .= sprintf('Ship, %s, 10000 (%s), %s, %s', $ship->name, $stats->getValue($ship->id), $classes[$ship->class], $races[$race]) . PHP_EOL;
				foreach ($t1[$classes[$ship->class]] as $t1Ship):
					$retVal .= sprintf('Stopped By, %s, [t1], %s' . PHP_EOL, $stats->getAllStoppedByShip($ship, $t1Ship, 1), (isset($roids[$t1Ship->race][$t1Ship->class])?'[roiding class]':''),);
				endforeach;
				if (isset($t2[$classes[$ship->class]])):
					foreach ($t2[$classes[$ship->class]] as $t2Ship):
						$retVal .= sprintf('Stopped By, %s, [t2]' . PHP_EOL, $stats->getAllStoppedByShip($ship, $t2Ship, 0.7));
					endforeach;
				endif;
				if (isset($t3[$classes[$ship->class]])):
					foreach ($t3[$classes[$ship->class]] as $t3Ship):
						if (isset($t3ship))
							$retVal .= sprintf('Stopped By, %s, [t3]' . PHP_EOL, $stats->getAllStoppedByShip($ship, $t3ship, 0.5));
					endforeach;
				endif;
			endforeach;
		endforeach;
		
		
		// return value
		return substr($retVal, 0, -1);
	}

}
