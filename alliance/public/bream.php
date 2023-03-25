<?php
echo "<h1 style='font-size:36px;'>VGN Intel Scraper - powered by BREAM</h1>";
echo "Copy the coords and paste, per ally, in the PA Intel Import page found at <a href='https://game.planetarion.com/alliance_intel.pl?view=import' target='_blank'>https://game.planetarion.com/alliance_intel.pl?view=import</a>";
echo "<br><br>";
echo "When complete, go to <a href='https://game.planetarion.com/alliance_intel.pl?view=export' target='_blank'>https://game.planetarion.com/alliance_intel.pl?view=export</a> If the intel appears as a solid block of nonsense, right-click and select 'view source'";
echo "<br><br>";
echo "Select all and copy and the paste into the webby intel parser at <a href='https://vgnpa.uk/#/admin' target='_blank'>https://vgnpa.uk/#/admin</a> if you can't see the Intel parser box, contact a webby admin";
echo "<br><br>";

$options = array(
  'http'=>array(
    'method'=>"GET",
    'header'=>"Accept-language: en\r\n" .
              "Mozilla/5.0 (Linux; Android 6.0.1; Nexus 5X Build/MMB29P) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/W.X.Y.Z Mobile Safari/537.36 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)\r\n" // i.e. An iPad 
  )
);
$context = stream_context_create($options);
for($i=0; $i<100; $i++) {
$link = "http://breampatools.ddns.net/PA/alliance_lookup.php?alli=" . $i;
try {
    $html = file_get_contents($link);
} catch(\Exception $e) {
    $response = "Couldn't read url!";
    return $response;
}
$lines = explode("\n", $html);
ForEach($lines as $line) {
    if(strpos($line, '</form>')) {
        $tbody = explode("<tbody><tr>", $line);
    }
}
if (!isset($tbody[1])) {
    continue;
}
$trs = explode("<tr><td style=\"text-align:center;\">", $tbody[1]);
$coords = "";
ForEach($trs as $tr) {
    preg_match('/planet_lookup\.php\?coords=(\d*:\d*:\d*)/', $tr, $match);
    $coords .= $match[1] . ", ";
}
if ($coords) {
    $pos = strpos($html, 'class="nomono"');
    $ally = substr($html, $pos + 40, 200);
    $ally = explode("</SPAN>", $ally);
    echo "Alliance: " . $ally[0] ." ". substr($coords, 0, -2) . " <button onClick='copyToClipboard(\"" . substr($coords, 0, -2) . "\")'>Copy to clipboard</button>";
    echo "<br><br>";
}
}

echo "<script>";
echo "function copyToClipboard(text) {";
echo "var dummy = document.createElement('textarea');";
echo "document.body.appendChild(dummy);";
echo "dummy.value = text;";
echo "dummy.select();";
echo "document.execCommand('copy');";
echo "document.body.removeChild(dummy);";
echo "alert('Coords copied to clipboard');";
echo "}";
echo "</script>";
?>
