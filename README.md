# Planetarion Alliance Toolset
A space sci-fi game Planetarion Alliance Toolset (with Telegram and SMS Support)

## Credits
Originally created by VenoX and enhanced by macen_pa

## Features

Webby:
 * Members - configure notifications, link to telegram
 * Politics - set deals so attack can't be incorrect
 * Schedule - afk hours of day
 * Planets - arbiter
 * Galaxies - arbiter
 * Alliances - arbiter
 * Scans - imported scans (see !parse <scan url>)
 * Attacks - attack system
 * Battlegroups - battlegroup system
 * Misc - various tools

Telegram:
 * !tools: Website URL
 * !tick: Display current tick or information for tick - Usage: !tick [tick]
 * !cookie: Give user a virtual cookie - Usage: !cookie <nick> <reason>
 * !setnick: Set your nick at round start - Usage: !setnick <nick>
 * !setplanet: Set your planet at round start - Usage: !setplanet <x:y:z>
 * !attacks: List open attacks
 * !claimed: List claimed targets from open attacks
 * !book: Book a target in an open attack, use !free to see available targets. - Usage: !book <x:y:z> <landing tick>
 * !drop: Drop a target from attack - Usage: !drop <x:y:z> <landing tick>
 * !free: List all available targets in attack
 * !launch: Retrieve all claimed attack targets
 * !call: Place a call to <nick> - Usage: !call <nick>
 * !sms: Send an SMS to <nick> - Usage: !sms <nick> <message>
 * !maxcap: Find max potential roid gain for <target> after <ticks of roiding> - Usage: !maxcap <x:y:z> [ticks]
 * !eff: Effect of ship on ships it targets - Usage:  !eff <amount> <ship> [tier]
 * !stop: Calculate ships required to stop ship with amount - Usage: !stop <ship> <amount>
 * !afford: Work out potential for ships to be built for a given planet - Usage: !afford <coords> <ship>
 * !roidcost: Cost of roids given based on supplied information - Usage: !roidcost <roids> <value loss> <mining bonus>
 * !ship: Retrieve stats for <ship> - Usage: !ship <ship name>
 * !last24: Fetch stats for last 24 ticks - Usage: !last24 [nick|alliance]
 * !top5: Retrieve top 5 players with option to specify an alliance - Usage: !top5 [alliance]
 * !lookup: Fetch your planets details - Usage: !lookup
 * !epenis: Last 24 hours score change - Usage: !epenis [nick]
 * !galpenis: Last 24 hours galaxy score change - Usage: !galpenis [nick]
 * !apenis: Last 24 hours alliance score change for Unicorns
 * !winners: Top 5 players in alliance
 * !loosers: Bottom 5 players in alliance
 * !intel: Set or retrieve intel for planet or galaxy - Usage: !intel <x:y:z> [nick]
 * !latestscan: Latest scan for given coords and type - Usage: !latestscan <x:y:z> <pdau>
 * !cost: Retrieve cost of given ships - Usage: !cost <amount> <ship>
 * !req: Request a scan - Usage: !req <x:y:z> <pduaj>
 * !reqs: List all open requests - Usage: scan/officer channel only
 * !parse: Parse given scan url - Usage: !parse <scan url>
 * !jpg: Parse scan link and notify those with prelaunch on them - Usage: !jpg <group scan url>
 * !spam: Spam intel for given alliance - Usage: !spam <alliance>
 * !whodidthis: Random love for those that helped with this project
 
 ## Installation
 
 In order to use tools you must have:
 - A working webserver with PHP 7.4 and MySQL
 - Email address for notifications (with tools+1@... support)
 - Telegram Bot (see @BotFather on TG for instructions)
 - Infobip account registered to send SMS 
 
 Follow these steps:
 1) Replace 'your.domain.tld' with your domain
 2) Enter credentials into `alliance/.env`
 3) Import database `patools_webby.sql`
 4) Run `php /path/to/tools/alliance/artisan schedule:run` on the hour every hour
 5) Configure Telegram webhook with https://your.domain.tld/<bot_key>/hook
 
 **Additional Steps**
 Run this in MySQL:
 INSERT INTO settings VALUES ('tg_channels','',NULL,NULL),('tg_notifications_channel','',NULL,NULL),('tg_scans_channel','', NULL, NULL);
 INSERT INTO roles VALUES (1,'Admin', NULL, NULL),(2,'BC',NULL,NULL),(3,'Member',NULL,NULL),(4,'Scanner',NULL,NULL),(5,'DC',NULL,NULL);
 
 ## Usage
 For attacks and Telegram to work you must do the following, every round:
 1) /addchannel or /addscanschannel to start Telegram monitoring
 2) Register at Tools, approve account
 3) Every member must do !setnick <tools_nick> on Telegram
 4) After shuffle, you must do !setplanet <xyz> or attack system won't work
	 
 ## Troubleshooting
  * You may need to adjust `public/.htaccess` to fit with your environment
  * See `roles` table after install, you may need to add basic data
  * When a new database, settings table may need a tg_channels [] entry

	
## LICENSE

The MIT License (MIT)

Copyright (c) 2018 Uber Technologies, Inc.

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
