<?php
namespace App\Telegram\Custom\Core;

use App\Telegram\Custom\BaseCommand;
use App\Planet;
use App\User;
use DB;

class LookupCommand extends BaseCommand
{
    protected $command = "!lookup";

    public function execute()
    {
        if(!$this->isWebUser()) return "User can not be authenticated with webby.";
        if(!$this->hasTelegramUsername()) return "Cannot use this command without a Telegram username.\n\n<strong>iPhone</strong>\n- Go to Settings in Telegram and click on Edit (Profile, top right)\n- Click on Username and specify one if not set already.\n\n<strong>Android</strong>\n- Go to Settings in Telegram\n- Click on Username and specify one if not set already.";

        // No text, try to find the users planet
        if(!$this->text) {
            $user = User::with('planet')->where('tg_username', $this->message->from['id'])->first();
            if(!$user) {
                return "You must link your TG account to your web user using !setnick <web username>.";
            }
            if(!$user->planet) {
                return "You haven't set your coords on webby, use !setplanet <x:y:z>.";
            }
            return $user->planet;
        }

        preg_match("/^(\d+)[.: ](\d+)[.: ](\d+)$/", $this->text, $planet);

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

            if($planet) {
                return $planet;
            } else {
                return sprintf("No planet found at %d:%d:%d", $x, $y, $z);
            }
        }
        else {
            $member = ltrim($this->text, '@');
            $tgdb = DB::table('user')->where('username', $member)->first();
            if(!isset($tgdb)) {
                $user = User::where('name', $member)->first();
                if(!isset($user->name)) {
                    $tgdb = DB::table('user')->where('first_name', 'like', '%' . $member . '%')->first();
                    if(!isset($tgdb)) {
                        return "No user found with this @telegram_username, webby_username or Telegram name: " . $member;
                    }
                    else {
                        $user = User::where('tg_username', $tgdb->id)->first();
                    }
                }
            }
            else {
                $user = User::where('tg_username', $tgdb->id)->first();
            }

            if(isset($user->planet)){
                return $user->planet;
            }
            else {
                return "This user has no planet set.";
            }
            
        }

        return "usage: !lookup <x:y:z|@telegram_username|webby_nickname>";
    }
}