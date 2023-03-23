<?php
namespace App\Telegram\Custom;

use App\Telegram\Custom\BaseCommand;
use App\Alliance;
use App\Planet;
use App\User;

class Top10Command extends BaseCommand
{
    protected $command = "!top10";

    public function execute() 
    {
        if(!$this->isWebUser()) return "User can not be authenticated with webby.";

        $subject = $this->text;
        $pattern = "/(?i)(ter|cat|xan|zik|etd)/";

        preg_match($pattern, $subject, $race);

        $race = ($race[1]) ?? null;

        $pattern = "/(?i)\b(?!ter|cat|xan|zik|etd)(\S+)/";

        preg_match($pattern, $subject, $ally);

        $ally = ($ally[1]) ?? null;

        if ($ally) {

            $alliance = Alliance::where('name', 'like', '%' . $ally . '%')->orWhere('nickname', $ally)->first();
            
            if ($race) {
                $planets = Planet::where([
                    ['alliance_id', $alliance->id],
                    ['race', $race],
                ])->orderby('score', 'desc')->take(10)->get();
                $response = "Showing top 10 " . strtoupper($race) . "'s for alliance: " . $alliance->name . "\n";
            } else {
                $planets = Planet::where('alliance_id', $alliance->id)->orderby('score', 'desc')->take(10)->get();
                $response = "Showing top 10 for alliance: " . $alliance->name . "\n";
            }
        } else {

            if ($race) {
                $planets = Planet::where('race', $race)->orderby('score', 'desc')->take(10)->get();
                $response = "Showing global top 10 " . strtoupper($race) . "'s:\n";
            } else {
        	   $planets = Planet::orderby('score', 'desc')->take(10)->get();
        	   $response = "Showing global top 10:\n";
            }
        }
        
        $data = [];
        foreach ($planets as $planet) {
            $nick = ($planet->nick) ? $planet->nick : "Unknown";
            $alliance = Alliance::where('id', $planet->alliance_id)->first();
            if (isset($alliance)) {
                $alliance = ($alliance->name) ? $alliance->name : "Unknown";
            } else {
                $alliance = "Unknown";
            }

        	$data[] = "[" . $alliance . "] " . $planet->x . ":" . $planet->y . ":" . $planet->z . " - " . $nick . " - " . $planet->race . " - " . number_format($planet->score) . " - " . number_format($planet->size);
        //	$data[] = "[" . $alliance . "] " . $planet->x . ":" . $planet->y . ":" . $planet->z . " " . $planet->ruler_name . " of " . $planet->planet_name . " (" . $nick . ") " . $planet->race . " " . $planet->score . " " . $planet->size;
        }

        $response = $response . implode("\n", $data);

        return $response;

	}
}