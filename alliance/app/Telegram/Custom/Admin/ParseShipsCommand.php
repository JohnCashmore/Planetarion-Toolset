<?php
namespace App\Telegram\Custom\Admin;

use Longman\TelegramBot\Request;
use App\Telegram\Custom\BaseCommand;
use App\User;
use App\Ship;
use Config;

class ParseShipsCommand extends BaseCommand
{
    protected $command = "!reloadstats";

    public function execute()
    {
        if(!$this->isWebUser()) return "User can not be authenticated with webby.";

        $user = User::where('tg_username', $this->userId)->first();

        if($user->role_id <> 1) {
            return "You're not an admin....";
        }
        
        $url = Config::get('stats.ships');

        $html = file_get_contents($url);

        $ships = simplexml_load_string($html);

        if($ships) {

            Ship::truncate();

            foreach($ships as $ship) {
                Ship::create([
                    'name' => $ship->name,
                    'class' => $ship->class,
                    't1' => $ship->target1,
                    't2' => ($ship->target2 != "-") ? $ship->target2 : null,
                    't3' => ($ship->target3 != "-") ? $ship->target3 : null,
                    'type' => $ship->type,
                    'init' => $ship->initiative,
                    'guns' => $ship->guns,
                    'armor' => $ship->armor,
                    'damage' => ($ship->damage != "-") ? $ship->damage : 0,
                    'empres' => $ship->empres,
                    'metal' => $ship->metal,
                    'crystal' => $ship->crystal,
                    'eonium' => $ship->eonium,
                    'total_cost' => $ship->metal + $ship->crystal + $ship->eonium,
                    'race' => $ship->race,
                    'eta' => $ship->baseeta
                ]);
            }
        }

        return "Loaded stats!";
    }
}