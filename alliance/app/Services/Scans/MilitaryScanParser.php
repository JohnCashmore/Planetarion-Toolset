<?php

namespace App\Services\Scans;


use App\MilitaryScan;
use App\Scan;
use App\Planet;
use App\Ship;
use App\ScanRequest;
use App\Services\Scans\ScanTrait;
use PHPHtmlParser\Dom;

class MilitaryScanParser
{
    use ScanTrait;

    public function execute()
    {
        $scan = $this->scan;
        $scanId = $this->scanId;
        $planetId = $this->planetId;
        $tick = $this->tick;
        $time = $this->time;



        $dom = new Dom;
        $dom->loadStr($scan);

        $rows = $dom->find("tr");

        $newScan = Scan::create([
            'pa_id'     => $scanId,
            'planet_id' => $planetId,
            'scan_type' => MilitaryScan::class,
            'tick'      => $tick,
            'time'      => $time
        ]);



        foreach ($rows as $row) {
            $shipName   = $row->firstChild()->firstChild();
            $base = $shipName->nextSibling()->firstChild();
            $fleet1 = $base->nextSibling()->firstChild();
            $fleet2 = $fleet1->nextSibling()->firstChild();
            $fleet3 = $fleet2->nextSibling()->firstChild();

            if ($shipName == 'Ship' || $shipName == 'TotalVisibleShips' || $shipName == 'TotalShips') {
                // not those rows
                continue;
            }


            $shipName = trim(implode(" ", preg_split('/(?=[A-Z])/', $shipName)));

            if($shipName == 'Pa Bear') {
                $shipName = 'PaBear';
            }

            if($shipName == 'Blackwidow') {
                $shipName = 'Black widow';
            }

            $ship = Ship::where('name', $shipName)->first();
            if (!$ship) {
                // Ship not found
                continue;
            }

            if ($ship) {

                $mScan = MilitaryScan::create([
                    'scan_id' => $newScan->id,
                    'ship_id' => $ship->id,
                    'base' => intval(str_replace(',', '', $base)),
                    'f1' => intval(str_replace(',', '', $fleet1)),
                    'f2' => intval(str_replace(',', '', $fleet2)),
                    'f3' => intval(str_replace(',', '', $fleet3))
                ]);
            };

        }

        if($request = ScanRequest::with(['planet', 'user'])->where(['planet_id' => $planetId, 'scan_type' => 'm'])->whereNull('scan_id')->get()) {
            foreach($request as $req) {
                $req->scan_id = $newScan->id;
                $req->save();
                $this->requestFulfilled($req);
            }
        }

        $planet = Planet::with('latestM')->where('id', $planetId)->first();

        if((isset($planet->latestM) && $planet->latestM->tick < $tick) || !$planet->latest_m || (isset($planet->latestM) && $planet->latestM->time < $newScan->time)) {
            $planet->latest_m = $newScan->id;
            $planet->save();
        }

        return true;
    }
}
