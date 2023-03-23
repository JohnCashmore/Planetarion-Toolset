<?php
namespace App\Telegram\Custom\Attacks;

use App\Telegram\Custom\BaseCommand;
use App\Alliance;
use App\Planet;
use App\User;

class BashCommand extends BaseCommand
{
    protected $command = "!bash";

    public function execute()
    {
        if(!$this->isWebUser()) return "User can not be authenticated with webby.";

        $string = explode(" ", $this->text);

        // Grab your own planet information...
        
        $user = User::with('planet')->where('tg_username', $this->message->from['id'])->first();
        if(!$user) {
            return "You must link your TG account to your web user using !setnick <web username>.";
        }
        if(!$user->planet) {
            return "You haven't set your coords on webby, use !setplanet <x:y:z>.";
        }
        $planet_id = $user->planet_id;

        $planet = Planet::where('id', $planet_id)->first();

        $score = $planet->score;
        $value = $planet->value;

        $min_value = ceil(0.5 * $value);
        $min_score = ceil(0.6 * $score);

        $reply = "You cannot attack planets with less than " . $min_value . " in value and " . $min_score . " in score.";
        
        $ally = ($string[0]) ?? null;

        if ($ally) {

            $alliance = Alliance::where('name', 'like', '%' . $ally . '%')->orWhere('nickname', $ally)->first();

            if (isset($alliance)) {
                $planets = Planet::where([
                    ['alliance_id', $alliance->id],
                    ['value', '>', $min_value],
                    ['score', '>', $min_score],
                ])->orderBy('x', 'ASC')->orderBy('y', 'ASC')->orderBy('z', 'ASC')->get();

                $data = [];
                $count_planets = count($planets);

                foreach ($planets as $planet) {
                    $data[] = $planet->x . ":" . $planet->y . ":" . $planet->z;
                }
                $reply = $reply . "\n" . "You can attack the following planets (" . $count_planets . ") in alliance " . $alliance->name . ":" . "\n" . implode(" ", $data);
            } else {
                return "No alliance found for: " . $string[0];
            }
        }


        return $reply;
    }
}