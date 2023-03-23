<?php

namespace App\Services\Misc;

use App\Ship;

class Stats
{
	private $name;
	private $amount;

	public function setId($id)
	{
		$this->id = $id;
		return $this;
	}

	public function setName($name)
	{
		$this->name = $name;
		return $this;
	}

	public function setAmount($amount)
	{
		$this->amount = $amount;
		return $this;
	}

	public function execute($amount = '10000')
	{

		// setup variables
		$reply = '';
		$shipsProcessed = array();
		
		// process data
		$ships = Ship::get();
		foreach($ships as $id => $ship):
			$shipsProcessed[] = $this->getAllStopped($ship);
		endforeach;	
		
		sort($shipsProcessed);
		foreach ($shipsProcessed as $shipProcessed):
			$reply .= $shipProcessed;
		endforeach;
			
		// return value
		echo $reply;
		return substr($reply, 0, -1);
	}
	public function getAllStopped ($ship) {
		
		// setup vars
		$valueNeeded = 0;
		$valueNumNeeded = 0;
		
		// get basic values for ship
	  	$value = $this->getValue($ship->id, '10000', true);
		$armour = $this->getArmour($ship->id, '10000', true);
		$eres = $this->getEmpres($ship->id, '10000', true);
		
		// loop for all ships that target ship
		$ships = $this->getShips($ship->id);
		foreach($ships as $eff => $targetGroup) {
			$eff = $eff / 100;
			foreach($targetGroup as $targetShip) {
				$stopped = $this->getStopped($ship->id, $targetShip->id, $eff, '10000', true);
				$value = $this->getValue($targetShip->id, $stopped, true);
				$valueNeeded = $valueNeeded + $value;
				$valueNumNeeded += 1;
			}
		}
		$valueAverage = intval($valueNeeded / $valueNumNeeded);
		$valueAmount = $this->getValue($ship->id, 10000, true);
		$valuePercentage = ($valueAmount / $valueAverage) * 100;

		return sprintf('(%03d) %s with value %s requires %s value on average to be stopped ' . PHP_EOL, number_shorten($valuePercentage, 0), $ship->name, number_shorten($this->getValue($ship->id, '10000', true), 1), number_shorten($valueNeeded, 2));

	}
	
	public function getAllStoppedByShip ($targetShip, $attackingShip, $attackingShipPower) {
		
		// setup vars
		$valueNeeded = 0;
		$valueNumNeeded = 0;
		
		// get basic values for ship
	  	$targetValue = $this->getValue($targetShip->id, '10000', true);
		$armour = $this->getArmour($targetShip->id, '10000', true);
		$eres = $this->getEmpres($targetShip->id, '10000', true);
		
		// loop for all ships that target ship
		$stopped = $this->getStopped($targetShip->id, $attackingShip->id, 10000, $attackingShipPower);
		$attackingValue = $this->getValue($attackingShip->id, $stopped, true);
		
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
		
		return sprintf('%s, %s (%s), %s, %s', $attackingShip->name, $stopped, number_shorten($attackingValue, 1), $classes[$attackingShip->class],  $races[$attackingShip->race]);

	}
	
	private function getStopped ($id, $targetId, $amount = 10000, $power = 1) {
		
		// check id and get ship
		if (!isset($id))
			throw new Exception('No id given to getStopped in Stats.php');
		if (!isset($targetId))
			throw new Exception('No targetId given to getStopped in Stats.php');
		$ship = Ship::find($id);
		$targetShip = Ship::find($targetId);
		
		// get values
	  	$value = $this->getValue($id, '10000', true);
		$armour = $this->getArmour($id, '10000', true);
		$eres = $this->getEmpres($id, '10000', true);


		// calculate ships needed to stop ship
		if($targetShip->type == 'EMP') {
			$stopped = ceil($amount / ((100 - $ship->empres) / 100) / ($targetShip->guns * $power));
		} else {
			$stopped = ceil($armour / ($targetShip->damage * $power));
		}

		// process stop
		if ($ship->init < $targetShip->init):
			$effCmd = new \App\Telegram\Custom\EffCommand();
			$effCmd->basic = $targetShip->name;
			$effCmd->text = $amount . ' ' . $ship->name;
			$stops = $effCmd->execute();
			if (is_numeric($stops))
				$stopped += $stops;
		endif;
		
		// return value
		return $stopped;
	}
	public function getValue ($id, $amount = '10000', $raw = false) {
		
		// check id and get ship
		if (!isset($id))
			throw new Exception('No id given to getValue in Stats.php');
		
		// get ship
		$ship = Ship::find($id);
		
		// return calculation
		if ($raw)
			return (($ship->metal + $ship->crystal + $ship->eonium) * $amount) / 100;
		else	
			return number_shorten((($ship->metal + $ship->crystal + $ship->eonium) * $amount) / 100, 1);

	}
	private function getArmour($id, $amount = '10000', $raw = false) {
		
		// check id and get ship
		if (!isset($id))
			throw new Exception('No id given to getArmour in Stats.php');
		
		$ship = Ship::find($id);

		if ($raw)
			return $ship->armor * $amount;
		else	
			return number_shorten((($ship->armor * $amount) / 100), 1);
		
		
	}
	
	private function getEmpres($id, $amount = '10000', $raw = false) {
		
		// check id and get ship
		if (!isset($id))
			throw new Exception('No id given to getEmpres in Stats.php');
		
		$ship = Ship::find($id);
		
		if ($raw)
			return $ship->empres * $amount;
		else
			return number_shorten((($ship->empres * $amount) / 100), 1);
			
			
		
	}
	private function getShips($id = false)
	{
		$ships = [];
		
		if (!is_bool($id) && $id){
			$ship = Ship::find($id);
			$ships[100] = Ship::where('t1', $ship->class)->get();
			if($targeted = Ship::where('t2', $ship->class)->get()) 
				$ships[70] = $targeted;
			if($targeted = Ship::where('t3', $ship->class)->get())
				$ships[50] = $targeted;
			
		} else {
			$ships[] = Ship::get();
		}
		
		return $ships;
	}
}
