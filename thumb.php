<?
/*
 * holarse-faboulous-screenshot-getter PHP Script
*/

// Pfad zum speicherort der Thumbnails, mit abschließendem /
$imagepath = "/var/www/html/images/";

// Standard Werte
$thumb = '160x160';
$viewport = '1440x900';
$delay = "0.5";

// Url überprüfen
if (isset($_GET['url']) && preg_match("@^https?://@", $_GET['url']) && filter_var($_GET['url'], FILTER_VALIDATE_URL) !== FALSE)
    $url = $_GET['url'];
else
    exit;

// Optionale Angaben überprüfen
if (isset($_GET['thumb']) && preg_match("/^[0-9]{2,4}x[0-9]{2,4}$/", $_GET['thumb']))
    $thumb = $_GET['thumb'];

if (isset($_GET['viewport']) && preg_match("/^[0-9]{3,4}x[0-9]{3,4}$/", $_GET['viewport']))
    $viewport = $_GET['viewport'];

if (isset($_GET['delay']) && preg_match("/^[0-9]\.[0-9]$/", $_GET['delay']))
    $delay = $_GET['delay'];

// MD5 der Url erstellen
$md5 = md5($url);

// Den Thumbnail ausgeben falls er schon existiert
if (file_exists($imagepath.$md5.'.png'))
{
    header('Content-Type: image/png');
    readfile($imagepath.$md5.'.png');
}
else // Ansonsten den Thumbnail erstellen und bei Erfolg ausgeben
{
    shell_exec("/usr/local/bin/hol_thumbnailer ".
                escapeshellarg($url)." ".
                escapeshellarg($viewport)." ".
                escapeshellarg($thumb)." ".
                escapeshellarg($delay)
            );

    if (file_exists($imagepath.$md5.'.png'))
    {
        header('Content-Type: image/png');
        readfile($imagepath.$md5.'.png');
    }
    else // Fallback Thumbnail
    {
        header('Content-Type: image/png');
        readfile($imagepath.'default.png');
    }
}
?>
