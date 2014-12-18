<?

function to_closure($func_name, $class_or_object = null)
{
    $f = $class_or_object
        ? array($class_or_object, $func_name)
        : $func_name;

    return function () use ($f) {
        return call_user_func_array($f, func_get_args());
    };
}

function get_callee()
{
    $backtrace = debug_backtrace();
    return isset($backtrace) ? $backtrace[1]['function'] : null;
}

/**
 * Checks date if matches given format and validity of the date.
 * Examples:
 * <code>
 * is_date('22.22.2222', 'mm.dd.yyyy'); // returns false
 * is_date('11/30/2008', 'mm/dd/yyyy'); // returns true
 * is_date('30-01-2008', 'dd-mm-yyyy'); // returns true
 * is_date('2008 01 30', 'yyyy mm dd'); // returns true
 * </code>
 * @param string $value the variable being evaluated.
 * @param string $format Format of the date. Any combination of <i>mm<i>, <i>dd<i>, <i>yyyy<i>
 * with single character separator between.
 */
function is_valid_date($value, $format = 'dd.mm.yyyy'){
    if(strlen($value) >= 6 && strlen($format) == 10){

        // find separator. Remove all other characters from $format
        $separator_only = str_replace(array('m','d','y'),'', $format);
        $separator = $separator_only[0]; // separator is first character

        if($separator && strlen($separator_only) == 2){
            // make regex
            $regexp = str_replace('mm', '(0?[1-9]|1[0-2])', $format);
            $regexp = str_replace('dd', '(0?[1-9]|[1-2][0-9]|3[0-1])', $regexp);
            $regexp = str_replace('yyyy', '(19|20)?[0-9][0-9]', $regexp);
            $regexp = str_replace($separator, "\\" . $separator, $regexp);
            if($regexp != $value && preg_match('/'.$regexp.'\z/', $value)){

                // check date
                $arr=explode($separator,$value);
                $day=$arr[0];
                $month=$arr[1];
                $year=$arr[2];
                if(@checkdate($month, $day, $year))
                    return true;
            }
        }
    }
    return false;
}

// usage:
// $allJson = preg_replace_callback('/\\\\u([0-9a-f]{4})/i', 'replace_unicode_escape_sequence', $allJson);
// but if it is json, encode to object and make a serialize! :)
function replace_unicode_escape_sequence($match)
{
    return mb_convert_encoding(pack('H*', $match[1]), 'UTF-8', 'UCS-2BE');
}

function search_replace_array($array, $search, $replace)
{
    foreach ($array as $key => $value) {
        if (is_array($value)) {
            $array[$key] = search_replace_array($value, $search, $replace);
        } else if (is_string($value) && $key != "css") {
            $array[$key] = str_replace($search, $replace, $value);
        }
    }

    return $array;
}

function getBodySize()
{
    // base-variable for PXtoEM calculation
    return 18; // -> the font-size of the body-element defined in css, change here if you change it in css!
}

function getEmFromPx($px)
{
    return round($px / getBodySize(), 2);
}

function getCurrentPageURL()
{
    if (!isset($_SERVER["QUERY_STRING"])) {
        return ""; // UnitTest
    }
    $pageURL = 'http';
    if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {
        $pageURL .= "s";
    }
    $pageURL .= "://";

    $parts = explode("&", $_SERVER["QUERY_STRING"]);
    $params = array();
    foreach ($parts as $part) {
        $obj = explode("=", $part);
        if ($obj[0] != "sessionId") {
            $params[] = $part;
        }
    }
    $queryString = count($params) > 0 ? "?" . array_join("&", $params) : "";
    $requestUri = strpos($_SERVER["REQUEST_URI"], "?") !== false ? substr($_SERVER["REQUEST_URI"], 0, strpos($_SERVER["REQUEST_URI"], "?")) : $_SERVER["REQUEST_URI"];

    if ($_SERVER["SERVER_PORT"] != "80") {
        $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $requestUri . $queryString;
    } else {
        $pageURL .= $_SERVER["SERVER_NAME"] . $requestUri . $queryString;
    }
    return $pageURL;
}

function getCurrentDomain()
{
    $url = getCurrentPageURL();
    $url = str_replace("http://", "", $url);
    $url = str_replace("https://", "", $url);
    return substr($url, 0, strpos($url, "/"));
}

function getSubdomain()
{
    $url = getCurrentPageURL();
    $url = str_replace("http://", "", $url);
    $url = str_replace("https://", "", $url);
    $url = substr($url, 0, strpos($url, "/"));
    if ($url == "127.0.0.1" || $url == "172.20.10.2") {
        return "";
    }

    $parts = explode(".", $url);
    $subdomain = "";
    if (count($parts) > 2) {
        // subdomain detected
        $subdomain = $parts[0];
    }
    if ($subdomain == "dev") {
        return "";
    }
    return $subdomain;
}

function getStackTraceStr() {
    $e = new Exception;
    return $e->getTraceAsString();
}

function ftp_is_dir($ftp, $dir)
{
    $pushd = ftp_pwd($ftp);

    if ($pushd !== false && @ftp_chdir($ftp, $dir)) {
        ftp_chdir($ftp, $pushd);
        return true;
    }

    return false;
}

/**
 * Copy directory and file structure
 *
 * @param $dir
 * @param $conn_id
 * @param $app
 */
function ftp_sync ($dir, $conn_id, $app) {

    $localDir = str_replace("/httpdocs", "", $dir);
    $localDir = $app->getSelfDir(true).$localDir;

    if ($dir != ".") {
        if (ftp_chdir($conn_id, $dir) == false) {
            echo ("Change Dir Failed: $dir<BR>\r\n");
            return;
        }
        if (!(is_dir($localDir)))
            mkdir($localDir);
        chdir ($localDir);
    }

    $contents = ftp_nlist($conn_id, ".");
    foreach ($contents as $file) {

        if ($file == '.' || $file == '..')
            continue;

        if (@ftp_chdir($conn_id, $file)) {
            ftp_chdir ($conn_id, "..");
            ftp_sync ($dir."/".$file, $conn_id, $app);
        } else {
            ftp_get($conn_id, $localDir."/".$file, $file, FTP_BINARY);
        }
    }

    ftp_chdir ($conn_id, "..");
    chdir ("..");

}

function getEmbed($url, $width, $height, $app)
{
    if ($url == "placeholder") {
        return '<p class="empty">'.$app->getTxt("embeds.placeholder")."</p>";
    } else if (strpos($url, "youtu") !== false) {
        if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match)) {
            $url = "//www.youtube.com/embed/" . $match[1] . "?wmode=transparent&fs=1&feature=oembed";
            return '<iframe src="' . $url . '" width="' . $width . '" height="' . $height . '" frameborder="0" allowfullscreen=""></iframe>';
        } else {
            return "";
        }
    } else if (strpos($url, "vimeo") !== false) {
        if (preg_match('/(\d+)/', $url, $match)) {
            return '<iframe src="//player.vimeo.com/video/' . $match[0] . '" width="' . $width . '" height="' . $height . '" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>';
        } else {
            return "";
        }
    } else if (strpos($url, "gate24.ch/Video.aspx") !== false) {
        return '<iframe src="' . $url . '" width="608" height="342" frameborder="0" scrolling="no"></iframe>';
    } else if (strpos($url, "soundcloud") !== false) {
        $track_id = null;
        if (function_exists(apc_fetch)) {
            $track_id = apc_fetch($url);
        }
        if (!isset($track_id) || strlen($track_id) == 0) {
            $apiUrl = 'https://api.soundcloud.com/resolve.json?url=' . $url . '&amp;client_id=YOUR_CLIENT_ID';
            $out = curl_download($apiUrl);
            $out = json_decode($out);
            preg_match('/(\d+)/', $out->location, $match);
            $track_id = $match[0];
            if (function_exists(apc_fetch)) {
                apc_store($url, $track_id);
            }
        }

        if (isset($track_id) && strlen($track_id) > 0 && is_numeric($track_id)) {
            return '<iframe width="100%" height="166" scrolling="no" frameborder="no" src="https://w.soundcloud.com/player/?url=http%3A%2F%2Fapi.soundcloud.com%2Ftracks%2F' . $track_id . '&amp;auto_play=false&amp;show_artwork=true&amp;color=ff7700"></iframe>';
        } else {
            return "";
        }
    } else {
        return "";
    }
}

function getUserBrowser()
{
    $u_agent = $_SERVER['HTTP_USER_AGENT'];
    $ub = '';
    if (preg_match('~MSIE|Internet Explorer~i', $u_agent) || (strpos($u_agent, 'Trident/7.0') !== false)) {
        $ub = "ie";
    } elseif (preg_match('/Firefox/i', $u_agent)) {
        $ub = "firefox";
    } elseif (preg_match('/Safari/i', $u_agent)) {
        $ub = "safari";
    } elseif (preg_match('/Chrome/i', $u_agent)) {
        $ub = "chrome";
    } elseif (preg_match('/Flock/i', $u_agent)) {
        $ub = "flock";
    } elseif (preg_match('/Opera/i', $u_agent)) {
        $ub = "opera";
    }

    return $ub;
}

function getUserBrowserVersion()
{
    $u_agent = $_SERVER['HTTP_USER_AGENT'];
    $v = '';

    if (preg_match('/rv:11/i', $u_agent)) {
        $v = "11";
    } else if (preg_match('/MSIE 10/i', $u_agent)) {
        $v = "10";
    } else if (preg_match('/MSIE 9/i', $u_agent)) {
        $v = "9";
    } else if (preg_match('/MSIE 8/i', $u_agent)) {
        $v = "8";
    } else if (preg_match('/MSIE 7/i', $u_agent)) {
        $v = "7";
    } else if (preg_match('/MSIE 6/i', $u_agent)) {
        $v = "6";
    } else if (preg_match('/rv:/i', $u_agent)) {
        $v = "Edge";
    }

    return intval($v);
}

function printEntriesClasses($entry) {
    if (!isset($entry)) {
        return "";
    }

    $result = "";
    foreach( $entry as $idx => $value ) {
        if (strcmp($value, "") == 0) {
            continue;
        }
        if (strpos($value, "-") !== false) {
            $parts = explode("-", $value);
            $value = $parts[0];
        }
        $result .= " ".$value;
    }

    return $result;
}

function resetCssInline($html)
{
    // reset css inline...
    $html = str_replace("<p style=\"", "<p style=\"margin: 0; ", $html);
    $html = str_replace("<p>", "<p style=\"margin: 0;\">", $html);
    $html = str_replace("<ul>", "<ul style=\"margin: 0;\">", $html);
    $html = str_replace("<a ", "<a style=\"text-decoration: none;\" ", $html);

    return $html;
}

function createCouponCode()
{
    $chars = "abcdefghijkmnopqrstuvwxyz023456789";
    srand((double)microtime() * 1000000);
    $i = 0;
    $pass = '';

    while ($i <= 5) {
        $num = rand() % 33;
        $tmp = substr($chars, $num, 1);
        $pass = $pass . $tmp;
        $i++;
    }

    return "#" . strtoupper($pass);
}

/**
 * Zufälliges Passwort generieren
 * @param int $length Anzahl Zeichen
 * @param int $strength Stärke (0-15 Binär)
 * @return string Passwort
 */
function createRandomPassword($length = 12, $strength = 15)
{
    $vowels = 'aeuy';
    $consonants = 'bdghjmnpqrstvz';
    if ($strength & 1)
        $consonants .= 'BDGHJLMNPQRSTVWXZ';
    if ($strength & 2)
        $vowels .= "AEUY";
    if ($strength & 4)
        $consonants .= '23456789';
    if ($strength & 8)
        $consonants .= '@#$%';

    do {
        $password = '';
        $alt = time() % 2;
        for ($i = 0; $i < $length; $i++) {
            if ($alt == 1) {
                $password .= $consonants[(rand() % strlen($consonants))];
                $alt = 0;
            } else {
                $password .= $vowels[(rand() % strlen($vowels))];
                $alt = 1;
            }
        }
    } while ($strength & 4 && !preg_match('/\d/', $password));

    return $password;
}

function getRGBAsHex($v) {
    $parts = explode(",", $v);

    $v1 = dechex(intval($parts[0]));
    if (strlen($v1) < 2) $v1 = '0' . $v1;
    $v2 = dechex(intval($parts[1]));
    if (strlen($v2) < 2) $v2 = '0' . $v2;
    $v3 = dechex(intval($parts[2]));
    if (strlen($v3) < 2) $v3 = '0' . $v3;
    $color = $v1 . $v2 . $v3;

    $opacity = null;
    if (isset($parts[3])) {
        $opacity = floatval($parts[3]) * 100;
    }
    return array($color, $opacity);
}

function getRGBA($color, $opacity, $returnExt = false)
{
    if ($color == "" || strcmp($color, "none") == 0 || strcmp($color, "transparent") == 0) {
        return "background-color: transparent; background: none; box-shadow: none; -moz-box-shadow: none; -webkit-box-shadow: none;";
    }

    if (strpos($color, "rgba(") !== false) {
        // haha funny...
        $v = str_replace("rgba(", "", $color);
        $v = str_replace(")", "", $v);
        list($color, $opacity) = getRGBAsHex($v);
    }

    $hex_alpha = '0123456789ABCDEF';
    $rgb = array();
    $rgb_uno = -1;
    $rgb_dos = -1;


    for ($i = 0; $i < 6; $i++) {
        $rgb_uno = strpos($hex_alpha, strtoupper(substr($color, $i, 1)));
        $rgb_dos = strpos($hex_alpha, strtoupper(substr($color, $i + 1, 1)));
        $dec = (intval($rgb_uno) * 16) + intval($rgb_dos);
        array_push($rgb, $dec);
        $i += 1;
    }

    //Ie7 and 8
    $ieOpacity = $opacity == 0 ? '00' : dechex(floor(floatval($opacity) / 100 * 255));
    $filter = "progid:DXImageTransform.Microsoft.Gradient(GradientType=1, StartColorStr='#" . ($ieOpacity . $color) . "', EndColorStr='#" . ($ieOpacity . $color) . "')";

    //css3 compatible browser
    $browser = getUserBrowser();
    $version = getUserBrowserVersion();
    if ($browser == "ie" && $version < 9) {
        $css = 'filter: ' . $filter . '; background: transparent;';
    } else {
        $css = 'background: rgba(' . array_join(", ", $rgb) . ', ' . floatval($opacity) / 100 . ');';
    }

    return !$returnExt ? $css : array($css, $opacity);
}

function array_join($str, $array)
{
    $result = "";
    foreach ($array as $key => $value) {
        if (strlen($result) != 0) {
            $result .= $str;
        }
        $result .= $value;
    }

    return $result;
}

function base64_credentials($user, $pass)
{
    return base64_decode($user . ":" . $pass);
}

/**
 * @param $url
 * @param string $method
 * @param null $post_data
 * @param null $headers
 * @return mixed|null
 */
function rest_call($url, $method = "get", $post_data = null, $headers = null)
{
    $output = curl_download($url, $method, $post_data, $headers);
    $json = null;
    $parts = explode("\n", $output);
    foreach ($parts as $part) {
        if (strpos($part, "{") !== false) {
            $json = json_decode($part, true);
        }
    }

    return !is_null($json) ? $json : $output;
}

/**
 * @param $url
 * @param string $method
 * @param null $post_data
 * @param null $headers
 * @param null $user_agent
 * @return int|mixed
 */
function curl_download($url, $method = "get", $post_data = null, $headers = null, $user_agent = null)
{
    // is cURL installed yet?
    if (!function_exists('curl_init')) {
        die('Sorry cURL is not installed!');
    }

    // OK cool - then let's create a new cURL resource handle
    $ch = curl_init();

    // Now set some options (most are optional)

    // do not check the name of SSL certificate of the remote server
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

    // do not check up the remote server certificate
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    // Set URL to download
    curl_setopt($ch, CURLOPT_URL, $url);

    // Set Method
    if ($method == "get") {
        curl_setopt($ch, CURLOPT_HTTPGET, true);
    } else if ($method == "post") {
        if (isset($post_data)) {
            //url-ify the data for the POST
            $fields_string = http_build_query($post_data);

            //set the url, number of POST vars, POST data
            curl_setopt($ch, CURLOPT_POST, count($post_data));
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
        } else {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, "empty body"); // without that line, curl will just not work :)
        }
    }

    // Set headers if available
    if (isset($headers)) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    }

    // Set a referer
    curl_setopt($ch, CURLOPT_REFERER, "http://" . getCurrentDomain());

    // User agent
    curl_setopt($ch, CURLOPT_USERAGENT, isset($user_agent) ? $user_agent : "Mozilla16/1.0");

    // Include header in result? (0 = yes, 1 = no)
    curl_setopt($ch, CURLOPT_HEADER, 0);

    // Should cURL return or print out the data? (true = return, false = print)
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    // Timeout in seconds
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);

    // Download the given URL, and return output
    $output = curl_exec($ch);
    $error_no = curl_errno($ch);

    // Close the cURL resource, and free system resources
    curl_close($ch);

    if ($error_no == 0) {
        return $output;
    } else {
        // look for error codes: http://curl.haxx.se/libcurl/c/libcurl-errors.html
        return $error_no;
    }
}

function get_escaped_html($str)
{
    $str = (string)str_replace(array("\""), "'", $str); // cleanup for a one-liner visually
    return trim($str);
}

function get_clean_html_for_json_transmission($html)
{
    $html = (string)str_replace(array("\r", "\r\n", "\n"), '', $html); // cleanup for a one-liner visually
    return trim($html);
}

function get_clean_json($obj)
{
    $result = str_replace("\\", "\\\\", str_replace("'", "\\'", json_encode($obj)));
    $result = str_replace("\"", "\\\"", $result);
    $result = str_replace("\\\\'", "\\'", $result);
    return $result;
}

function get_clean_json_for_html($obj)
{
    $result = json_encode($obj);
    $result = str_replace('"', "'", $result);
    return $result;
}

function get_clean_jsonobj($str)
{
    $lines = explode("\n", $str);
    $clean = array();
    foreach ($lines as $idx => $line) {
        if (strpos($line, "/*") !== false) {
            $newLine = substr($line, 0, strpos($line, "/*"));
            if ($newLine != "") {
                $clean[] = $newLine;
            }
        } else {
            $clean[] = $line;
        }
    }
    $cleanStr = array_join("\n", $clean);
    $obj = json_decode($cleanStr, true);
    return $obj;
}

function get_clean_anything($anything)
{
    $result = str_replace("\\", "\\\\", str_replace("'", "\\'", $anything));
    $result = str_replace("\\\\'", "\\'", $result);
    $result = str_replace("\"", "\\\\\"", $result);
    $result = str_replace("\n", "", $result);
    $result = str_replace("\r", "", $result);
    $result = str_replace("%", "$$", $result);
    return $result;
}

function escapeForDb($input, $dontEscapeSlash = false)
{
    $result = str_replace("\\", "\\\\", str_replace("'", "\\'", $input));
    $result = str_replace("\\\\'", "\\'", $result);
    if (!$dontEscapeSlash) {
        $result = str_replace("/", "\\\\/", $result);
    }
    return $result;
}

function get_clean_utf8_json_string($data)
{
    // we must do that so we have encoded utf-8 in our string and not stuff like \fc022
    if (is_string($data)) {
        $data = json_decode($data, true);
    }
    return serialize($data);
}

function get_unescaped_string($str)
{
    $str = str_replace("\\\\", "", $str);
    $str = str_replace("\\", "", $str);
    return str_replace("\\\”", "", $str);
}

function makeNsUnique($ns1, $ns2, $ns3, $ns4) {
    $all = array($ns1, $ns2, $ns3, $ns4);
    $map = array();
    foreach ($all as $ns) {
        $map[$ns] = true;
    }
    $n1 = "";
    $n2 = "";
    $n3 = "";
    $n4 = "";
    foreach ($map as $v => $unique) {
        if (strlen($n1) == 0) {
            $n1 = $v;
            continue;
        }
        if (strlen($n1) > 0 && strlen($n2) == 0) {
            $n2 = $v;
            continue;
        }
        if (strlen($n2) > 0 && strlen($n3) == 0) {
            $n3 = $v;
            continue;
        }
        if (strlen($n3) > 0 && strlen($n4) == 0) {
            $n4 = $v;
            continue;
        }
    }

    return array($n1, $n2, $n3, $n4);
}

function microtime_float()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}

function getRemainingMonths($product, $month)
{
    $now = new DateTime();
    $end = strtotime($product["end"]);
    $diff = $end - $now->getTimestamp();
    return round($diff / $month, 1, PHP_ROUND_HALF_UP);
}

function renderTimestamp($diff)
{
    $hours = 0;
    $minutes = 0;
    $seconds = $diff;

    if ($seconds >= 60) {
        $minutes = $seconds / 60;
        $minutes = round($minutes, 0, PHP_ROUND_HALF_DOWN);
        $seconds = $seconds - ($minutes * 60);
    }
    if ($seconds >= 60) {
        $minutes = $seconds / 60;
        $minutes = round($minutes, 0, PHP_ROUND_HALF_DOWN);
        $seconds = $seconds - ($minutes * 60);
    }
    if ($minutes >= 60) {
        $hours = $minutes / 60;
        $hours = round($hours, 0, PHP_ROUND_HALF_DOWN);
        $minutes = $minutes - ($hours * 60);
    }

    $seconds = round($seconds, 2, PHP_ROUND_HALF_UP);

    $result = "";
    if ($seconds > 0) {
        $result = $seconds . " sec ";
    }
    if ($minutes > 0) {
        $result = $minutes . " min " . $seconds . " sec ";
    }
    if ($hours > 0) {
        $result = $hours . " hrs " . $minutes . " min " . $seconds . " sec ";
    }
    return $result;
}

function truncate_blog($str, $count, $optionalMax = null)
{
    $defaultMax = 280;
    switch (intval($count)) {
        case 1:
            $defaultMax = 800;
            break;
        case 2:
            $defaultMax = 400;
            break;
        case 3:
            $defaultMax = 300;
            break;
    }

    $max = isset($optionalMax) ? $optionalMax : $defaultMax;
    if ($max > strlen($str)) {
        return $str;
    }
    $truncated = substr($str, 0, $max);
    $found = false;
    for ($i = strlen($truncated), $len = 0; $i > $len; $i--) {
        $c = substr($truncated, $i, 1);
        if ($c == "." || $c == "!" || $c == "?") {
            $truncated = substr($truncated, 0, $i + 1);
            $found = true;
            break;
        }
    }
    if (!$found) {
        return truncate_blog($str, $count, $max + 100);
    }
    $lastP = strpos($truncated, "</p>");
//    echo var_dump($lastP)."\n";
    if ($lastP !== false) {
        $diff = strlen($truncated) - $lastP;
//        echo var_dump($diff)."\n";
        if ($diff < 300) {
            // we have another truncated p detected that is not long enough to make sense.. cut it out!
            $truncated = substr($truncated, 0, $lastP);
        }
    }
    $truncated .= "</p>";
    return $truncated;
}

function encryptEmail($mailAdr, $mailLabel = "", $mailClass = "")
{
    if ($mailLabel == "") {
        $mailLabel = $mailAdr;
    }
    if ($mailClass != "") {
        $mailClass = ' class="' . $mailClass . '"';
    }
    $emailArray = explode("@", $mailAdr);
    $encryptEmail = '' . strrev($mailLabel) . '';

    return $encryptEmail;
}

// strpos that takes an array of values to match against a string
// note the stupid argument order (to match strpos)
function strpos_arr($haystack, $needle)
{
    if (!is_array($needle)) $needle = array($needle);
    foreach ($needle as $what) {
        if (($pos = strpos($haystack, $what)) !== false) return $pos;
    }
    return false;
}

function str_replace_first($search, $replace, $subject)
{
    $pos = strpos($subject, $search);
    if ($pos !== false) {
        $subject = substr_replace($subject, $replace, $pos, strlen($search));
    }
    return $subject;
}

function rm($path, $callee, $app)
{
    // our own delete-files function
    // to find the last few errors: don't remove the file actually, we just move it to a new location /deleted/<callee>
//    $targetBase = selfDir()."/deleted";
//    if (is_dir($targetBase)) {
//        mkdir($targetBase);
//    }
//    $targetDir = $targetBase."/".$callee;
//    if (is_dir($targetDir)) {
//        mkdir($targetDir);
//    }
//    $parts = explode("/", $path);
//    $filename = $parts[count($parts) - 1];
//
//    if ($app->isAuthorized()) {
//        return rename($path, $targetDir."/".$filename);
//    } else {
    try {
//        $fileName = get_file_name($path);
//        $path = str_replace($fileName, "", $path);
//        return rename($path.$fileName, $path."_deleted".$fileName);

        $success = unlink($path);

        // check if the folder is empty now, if yes, delete folder
        $parts = explode("/", $path);
        $dir = "/" . array_join("/", array_slice($parts, 0, count($parts) - 1));
        if (get_file_count($dir) == 0 && strpos($dir, "/copy") === false) {
            rmdir($dir);
            // check if product is empty now, if yes, delete folder
            $dir = "/" . array_join("/", array_slice($parts, 0, count($parts) - 2));
            if (get_file_count($dir) == 0) {
                rmdir($dir);
            }
        }

        return $success;
    } catch (ErrorException $e) {
        error_log("Cannot delete file!");
        error_log($e);
    }

//    }
}

function get_file_count($dir)
{
    $i = 0;
    if ($handle = opendir($dir)) {
        while (($file = readdir($handle)) !== false) {
            if (!in_array($file, array('.', '..')) && !is_dir($dir . $file))
                $i++;
        }
    }

    return $i;
}

function extract_fonts($json, $encode = true)
{
    if ($encode) {
        $jsonStr = get_clean_utf8_json_string($json);
    } else {
        $jsonStr = $json;
    }

    $fonts = array();
    $parts = explode("custom-styles", $jsonStr);
    foreach ($parts as $part) {
        $styles = substr($part, strpos($part, "style=\"") + 7, strlen($part));
        $styles = substr($styles, 0, strpos($styles, ">"));
        if (strpos($styles, "font-family") !== false) {
            $fams = explode("font-family:", $styles);
            if (count($fams) > 0) {
                $family = $fams[1];
                $family = substr($family, 0, strpos($family, ";"));
                // extract family only, no fallbacks, no quotes
                if (strpos($family, "\"") !== false) {
                    $family = substr($family, strpos($family, "\""), strripos($family, "\""));
                }
                if (strpos($family, "'") !== false) {
                    $family = substr($family, strpos($family, "'"), stripos($family, "'"));
                }
                if (strpos($family, ",") !== false) {
                    $subs = explode(",", $family);
                    $family = $subs[0];
                }
                $family = str_replace("\"", "", $family);
                $family = str_replace("'", "", $family);
                array_push($fonts, trim($family));
            }
        }
    }

    return $fonts;
}

function extract_files($json, $encode = true)
{
    if ($encode) {
        $jsonStr = get_clean_utf8_json_string($json);
    } else {
        $jsonStr = $json;
    }
    $files = array();
    while (strpos($jsonStr, "userfiles") !== false) {
        $extract = substr($jsonStr, strpos($jsonStr, "userfiles"), strlen($jsonStr));
        if (substr($extract, 0, 1) == "\"" || substr($extract, 0, 1) == "'") {
            $extract = substr($extract, 1, strlen($extract));
        }
        if (strpos($extract, "'") !== false && strpos($extract, "\"") === false || strpos($extract, "'") !== false && strpos($extract, "\"") !== false && strpos($extract, "'") < strpos($extract, "\"")) {
            $extract = substr($extract, 0, strpos($extract, "'"));
        } else if (strpos($extract, "\"") !== false) {
            $extract = substr($extract, 0, strpos($extract, "\""));
        }
        if (substr($extract, 0, 1) != "/") {
            $extract = "/" . $extract;
        }
        $files[] = $extract;
        $jsonStr = substr($jsonStr, strpos($jsonStr, "userfiles") + strlen($extract), strlen($jsonStr));
    }
    while (strpos($jsonStr, "/img/") !== false) {
        $extract = substr($jsonStr, strpos($jsonStr, "/img/"), strlen($jsonStr));
        $extract = substr($extract, 0, strpos($extract, "\""));
        if (substr($extract, 0, 1) != "/") {
            $extract = "/" . $extract;
        }
        $files[] = $extract;
        $jsonStr = substr($jsonStr, strpos($jsonStr, "/img/") + strlen($extract), strlen($jsonStr));
    }

    return $files;
}

function str_replace_last($search, $replace, $subject)
{
    $pos = strrpos($subject, $search);

    if($pos !== false)
    {
        $subject = substr_replace($subject, $replace, $pos, strlen($search));
    }

    return $subject;
}

function extract_base64_images($html)
{
    $files = array();
    while (strpos($html, "data:image") !== false) {
        $extract = substr($html, strpos($html, "data:image"), strlen($html));
        $extract = substr($extract, 0, strpos($extract, "\""));
        $data = substr($extract, strpos($extract, "base64") + 7, strlen($extract));
        $files[] = array(
            "search" => $extract,
            "data" => $data,
            "prefix" => str_replace($data, "", $extract)
        );
        $html = substr($html, strpos($html, "data:image") + strlen($extract), strlen($html));
    }

    return $files;
}

function base64_to_image($base64_string, $output_file)
{
    $ifp = fopen($output_file, "wb");
    fwrite($ifp, base64_decode($base64_string));
    fclose($ifp);
    return ($output_file);
}

function text_to_base64_image($text, $font = "Arial")
{
    putenv('GDFONTPATH=' . realpath('.'));

    // Set the content-type
//    header('Content-type: image/png');

    // Create the image
    $im = imagecreatetruecolor(400, 30);

    // Create some colors
    $white = imagecolorallocate($im, 255, 255, 255);
    $grey = imagecolorallocate($im, 128, 128, 128);
    $black = imagecolorallocate($im, 0, 0, 0);
    imagefilledrectangle($im, 0, 0, 399, 29, $white);

    // Replace path by your own font path

    // Add some shadow to the text
    imagettftext($im, 20, 0, 11, 21, $grey, $font, $text);

    // Add the text
    imagettftext($im, 20, 0, 10, 20, $black, $font, $text);

    // start buffering
    ob_start();
    // Using imagepng() results in clearer text compared with imagejpeg()
    imagepng($im);
    $contents = ob_get_contents();
    ob_end_clean();

    // Read image path, convert to base64 encoding
    $imgData = base64_encode($contents);

    // Format the image SRC:  data:{mime};base64,{data};
    $src = 'data: png;base64,' . $imgData;

    imagedestroy($im);

    return $src;
}

function hide_email($email)
{
    $character_set = '+-.0123456789@ABCDEFGHIJKLMNOPQRSTUVWXYZ_abcdefghijklmnopqrstuvwxyz';
    $key = str_shuffle($character_set);
    $cipher_text = '';
    $id = 'e' . rand(1, 999999999);
    for ($i = 0; $i < strlen($email); $i += 1) $cipher_text .= $key[strpos($character_set, $email[$i])];
    $script = 'var a="' . $key . '";var b=a.split("").sort().join("");var c="' . $cipher_text . '";var d="";';
    $script .= 'for(var e=0;e<c.length;e++)d+=b.charAt(a.indexOf(c.charAt(e)));';
    $script .= 'document.getElementById("' . $id . '").innerHTML="<a href=\\"mailto:"+d+"\\">"+d+"</a>"';
    $script = "eval(\"" . str_replace(array("\\", '"'), array("\\\\", '\"'), $script) . "\")";
    $script = '<script type="text/javascript">/*<![CDATA[*/' . $script . '/*]]>*/</script>';
    return '<span id="' . $id . '">[javascript protected email address]</span>' . $script;
}

function unset_ext($arr, $field)
{
    if (isset($arr[$field])) {
        unset($arr[$field]);
    }
}

function selfDir()
{
    $parts = explode("/", dirname(__FILE__));
    $path = "";
    foreach ($parts as $part) {
        if (strcmp($path, "") != 0) {
            $path .= "/";
        }
        $path .= $part;
        if (strcmp($part, "httpdocs") == 0 || strcmp($part, "dev") == 0) {
            break;
        }
    }
    return $path;
}

function fixPaypalAmount($amount)
{
    if (strpos($amount, ".") !== false) {
        $parts = explode(".", $amount);
        if ($parts[1] == "-") {
            return $parts[0] . ".00";
        } else {
            return $parts[0] . "." . $parts[1];
        }
    } else {
        return $amount . ".00";
    }
}

function getContentShadowValue($value, $reverse = false)
{
    $mapping = array(
        0 => 0,
        25 => 15,
        50 => 32,
        75 => 47,
        100 => 64
    );

    if ($reverse) {
        foreach ($mapping as $idx => $map) {
            if ($map == $value) {
                $value = $idx;
                break;
            }
        }
    } else if (isset($mapping[$value])) {
        $value = $mapping[$value];
    }

    return $value;
}

function getContentBorderRadiusValue($value, $reverse = false)
{
    $mapping = array(
        0 => 0,
        20 => 2,
        40 => 4,
        60 => 8,
        80 => 16,
        100 => 32
    );

    if ($reverse) {
        foreach ($mapping as $idx => $map) {
            if ($map == $value) {
                $value = $idx;
                break;
            }
        }
    } else if (isset($mapping[$value])) {
        $value = $mapping[$value];
    }

    return $value;
}

function hasDeliveryOption($options, $value)
{
    $result = false;
    if (is_null($options)) {
        return $result;
    }

    foreach ($options as $idx => $v) {
        if ($v == $value) {
            $result = true;
            break;
        }
    }

    return $result;
}

function hasPaymentOption($options, $value)
{
    $result = false;
    if (is_null($options)) {
        return $result;
    }

    foreach ($options as $idx => $v) {
        if ($v == $value) {
            $result = true;
            break;
        }
    }

    return $result;
}

function getOrderNumber($soId)
{
    $orderNumber = $soId . "";
    if (strlen($orderNumber) < 6) {
        while (strlen($orderNumber) < 6) {
            $orderNumber = "0" . $orderNumber;
        }
    }
    return $orderNumber;
}

/**
 * @param $translations
 * @param $prefix
 * @return array
 */
function filterTransWithPrefix($translations, $prefix)
{
    $filtered = array();
    foreach ($translations as $key => $value) {
        if (strpos($key, $prefix) !== false) {
            $filtered[$key] = $value;
        }
    }

    return $filtered;
}

function simpleHtmlFormat($input)
{
    $input = str_replace("\n", "</br>", $input);

    return $input;
}

function varDumpAsVar($var, $withHtmlFormat = true)
{
    ob_start(); // start output buffering -> nothing will be sent to the client
    var_dump($var);
    $content = ob_get_contents(); // get the output as a string
    ob_end_clean(); // silently close

    if ($withHtmlFormat) {
        return simpleHtmlFormat($content);
    } else {
        return $content;
    }
}

function getFirstNavigation($navigation)
{
    $homePage = null;
    $aPage = null;
    foreach ($navigation as $idx => $values) {
        if ($values["type"] == "page" || $values["type"] == "anchor") {
            if ($values["id"] == "106a6c241b8797f52e1e77317b96a201") {
                $homePage = $values;
            }
            if (!isset($aPage)) {
                $aPage = $values;
            }
        }
        if (isset($homePage) && isset($aPage)) {
            break;
        }
    }
    if (isset($homePage)) {
        return $homePage;
    } else if (isset($aPage)) {
        return $aPage;
    } else {
        return null;
    }
}

function get_file_name($path)
{
    $parts = explode("/", $path);
    return $parts[count($parts) - 1];
}

function get_relative_path($path)
{
    $parts = explode("/", $path);
    $relative = array();
    $start = false;
    foreach ($parts as $part) {
        if ($start) {
            $relative[] = $part;
        }
        if ($part == "htdocs" || $part == "httpdocs") {
            $start = true;
        }
    }
    if (!$start) {
        return $path;
    }
    return "/" . array_join("/", $relative);
}

function get_type_from_path($path)
{
    $parts = explode("/", $path);
    foreach ($parts as $idx => $part) {
        if ($part == "userfiles") {
            return $parts[$idx + 2];
        }
    }
    return "website";
}

function normalize($str)
{
    if (class_exists("Normalizer")) {
        $str = \Normalizer::normalize($str);
    } else {
        error_log("Normalizer not available, this can cause random deletes on files with special characters!");
    }
    return $str;
}

function rand_char_seq() {
    $seed = str_split('abcdefghijklmnopqrstuvwxyz'
        .'ABCDEFGHIJKLMNOPQRSTUVWXYZ'
//        .'0123456789!@#$%^&*()' // and any other characters
    );
    shuffle($seed); // probably optional since array_is randomized; this may be redundant
    $rand = '';
    foreach (array_rand($seed, 5) as $k) $rand .= $seed[$k];

    return $rand;
}

function detect_encoding($string, $enc = null)
{

    static $list = array('utf-8', 'iso-8859-1', 'windows-1251');

    foreach ($list as $item) {
        $sample = iconv($item, $item, $string);
        if (md5($sample) == md5($string)) {
            if ($enc == $item) {
                return true;
            } else {
                return $item;
            }
        }
    }
    return null;
}

function pacrypt($pw, $pw_db = "")
{
    $pw = stripslashes($pw);
    $password = "";
    $salt = "";

    $split_salt = preg_split('/\$/', $pw_db);
    if (isset ($split_salt[2])) {
        $salt = $split_salt[2];
    }
    $password = md5crypt($pw, $salt);

    return $password;
}

//
// md5crypt
// Action: Creates MD5 encrypted password
// Call: md5crypt (string cleartextpassword)
//

function md5crypt($pw, $salt = "", $magic = "")
{
    $MAGIC = "$1$";

    if ($magic == "") $magic = $MAGIC;
    if ($salt == "") $salt = create_salt();
    $slist = explode("$", $salt);
    if ($slist[0] == "1") $salt = $slist[1];

    $salt = substr($salt, 0, 8);
    $ctx = $pw . $magic . $salt;
    $final = hex2bin(md5($pw . $salt . $pw));

    for ($i = strlen($pw); $i > 0; $i -= 16) {
        if ($i > 16) {
            $ctx .= substr($final, 0, 16);
        } else {
            $ctx .= substr($final, 0, $i);
        }
    }
    $i = strlen($pw);

    while ($i > 0) {
        if ($i & 1) $ctx .= chr(0);
        else $ctx .= $pw[0];
        $i = $i >> 1;
    }
    $final = hex2bin(md5($ctx));

    for ($i = 0; $i < 1000; $i++) {
        $ctx1 = "";
        if ($i & 1) {
            $ctx1 .= $pw;
        } else {
            $ctx1 .= substr($final, 0, 16);
        }
        if ($i % 3) $ctx1 .= $salt;
        if ($i % 7) $ctx1 .= $pw;
        if ($i & 1) {
            $ctx1 .= substr($final, 0, 16);
        } else {
            $ctx1 .= $pw;
        }
        $final = hex2bin(md5($ctx1));
    }
    $passwd = "";
    $passwd .= to64(((ord($final[0]) << 16) | (ord($final[6]) << 8) | (ord($final[12]))), 4);
    $passwd .= to64(((ord($final[1]) << 16) | (ord($final[7]) << 8) | (ord($final[13]))), 4);
    $passwd .= to64(((ord($final[2]) << 16) | (ord($final[8]) << 8) | (ord($final[14]))), 4);
    $passwd .= to64(((ord($final[3]) << 16) | (ord($final[9]) << 8) | (ord($final[15]))), 4);
    $passwd .= to64(((ord($final[4]) << 16) | (ord($final[10]) << 8) | (ord($final[5]))), 4);
    $passwd .= to64(ord($final[11]), 2);
    return $magic . $salt . "\$" . $passwd;
}

function create_salt()
{
    srand((double)microtime() * 1000000);
    $salt = substr(md5(rand(0, 9999999)), 0, 8);
    return $salt;
}

if (!function_exists('hex2bin')) { # PHP around 5.3.8 includes hex2bin as native function - http://php.net/hex2bin
    function hex2bin($str)
    {
        $len = strlen($str);
        $nstr = "";
        for ($i = 0; $i < $len; $i += 2) {
            $num = sscanf(substr($str, $i, 2), "%x");
            $nstr .= chr($num[0]);
        }
        return $nstr;
    }
}

function to64($v, $n)
{
    $ITOA64 = "./0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
    $ret = "";
    while (($n - 1) >= 0) {
        $n--;
        $ret .= $ITOA64[$v & 0x3f];
        $v = $v >> 6;
    }
    return $ret;
}

function get_option_name($multiple, $nameField, $field, $index)
{
    if ($multiple) {
        return $field . "[" . $index . "][" . $nameField . "]";
    } else {
        return $field . $nameField;
    }
}

function exists_in_position($positions, $value) {
    $found = false;
    foreach ($positions as $cId => $c) {
        foreach ($c["entries"] as $x => $yRow) {
            foreach ($yRow as $y => $v) {
                if ($value == $v) {
                    $found = true;
                    break;
                }
            }
            if ($found) {
                break;
            }
        }
        if ($found) {
            break;
        }
    }

    return $found;
}

/**
 * @param $obj
 * @param $partial
 * @return mixed
 */
function filterPosition($obj, $partial) {
    $positions = $obj["position"];
    $newPositions = array();
    foreach ($positions as $cId => $c) {
        $newPositions[$cId] = array("type" => $c["type"], "entries" => array());
        foreach ($c["entries"] as $x => $yRow) {
            foreach ($yRow as $y => $value) {
                if (strpos($value, $partial) === false) {
                    if (!isset($newPositions[$cId]["entries"][$x])) {
                        $newPositions[$cId]["entries"][$x] = array();
                    }
                    $newPositions[$cId]["entries"][$x][$y] = $value;
                }
            }
        }
    }

    $obj["position"] = cleanup_positions($newPositions);
    return $obj;
}

function full_shift($arr) {
    $idx = -1;
    $value = "";
    foreach ($arr as $idx => $value) {
        break;
    }
    $filtered = array();
    foreach ($arr as $i => $v) {
        if ($i != $idx) {
            $filtered[$i] = $v;
        }
    }

    return array($idx, $value, $filtered);
}

function get_merge_struc($positions) {
    $idx = count($positions) - 1;
    foreach ($positions[$idx]["entries"] as $x => $yRow) {
        foreach ($yRow as $y => $value) {
            if ($value == "footer") {
                $idx--;
                break;
            }
        }
    }

    return $idx;
}

function get_merge_column($c) {
    switch ($c["type"]) {
        case "one-columns":
            return 0;

        case "two-columns":
            return rand(0, 1);

        case "three-columns":
            return rand(0, 2);

        default:
            return 0;

    }
}

function cleanup_positions($positions, $isNewsEditor = false)
{
    $cleanPosition = array();
    $navigationFound = false;
    $headerFound = false;
    $footerFound = false;
    $shoppingFound = false;
    $shoppingCartFound = false;
    $subscribersFound = false;
    $hoursFound = false;
    $linksFound = false;
    foreach ($positions as $cId => $c) {
        $cleanEntries = array();
        foreach ($c["entries"] as $x => $yRow) {
            $cleanYRow = array();
            if (is_array($yRow)) {
                foreach ($yRow as $y => $value) {
                    if (!is_null($value)) {
                        if ($value == "navigation" && $navigationFound) {
                            break;
                        } else if ($value == "navigation") {
                            $navigationFound = true;
                        }
                        if ($value == "footer" && $footerFound) {
                            break;
                        } else if ($value == "footer") {
                            $footerFound = true;
                        }
                        if ($value == "header" && $headerFound) {
                            break;
                        } else if ($value == "header") {
                            $headerFound = true;
                        }
                        if ($value == "shopping" && $shoppingFound) {
                            break;
                        } else if ($value == "shopping") {
                            $shoppingFound = true;
                        }
                        if ($value == "shopping_cart" && $shoppingCartFound) {
                            break;
                        } else if ($value == "shopping_cart") {
                            $shoppingCartFound = true;
                        }
                        if ($value == "subscribers" && $subscribersFound) {
                            break;
                        } else if ($value == "subscribers") {
                            $subscribersFound = true;
                        }
                        if ($value == "hours" && $hoursFound) {
                            break;
                        } else if ($value == "hours") {
                            $hoursFound = true;
                        }
                        if ($value == "links" && $linksFound) {
                            break;
                        } else if ($value == "links") {
                            $linksFound = true;
                        }
                        array_push($cleanYRow, $value);
                    }
                }
            }
            array_push($cleanEntries, $cleanYRow);
        }

        $amount = -1;
        switch ($c["type"]) {
            case "one-columns":
                $amount = 1;
                break;
            case "two-columns":
                $amount = 2;
                break;
            case "three-columns":
                $amount = 3;
                break;
        }

        if (count($cleanEntries) < $amount) {
            for ($i = 0, $len = $amount - count($cleanEntries); $i < $len; $i++) {
                array_push($cleanEntries, array());
            }
        }
        $cleanPosition[$cId] = array(
            "type" => $c["type"],
            "entries" => $cleanEntries
        );
    }

    if (!$navigationFound && !$isNewsEditor) {
        // no way! smuggle it into first structure, first column
        array_unshift($cleanPosition[0]["entries"][0], "navigation");
    }
    if (!$headerFound) {
        // no way! smuggle it into first structure, first column, last pos
        array_push($cleanPosition[0]["entries"][0], "header");
    }
    if (!$shoppingFound) {
        array_push($cleanPosition[0]["entries"][0], "shopping");
    }
    if (!$shoppingCartFound) {
        array_push($cleanPosition[0]["entries"][0], "shopping_cart");
    }
    if (!$subscribersFound) {
        array_push($cleanPosition[0]["entries"][0], "subscribers");
    }
    if (!$hoursFound) {
        array_push($cleanPosition[0]["entries"][0], "hours");
    }
    if (!$linksFound) {
        array_push($cleanPosition[0]["entries"][0], "links");
    }
    if (!$footerFound && !$isNewsEditor) {
        // no way! smuggle it into last structure if one-column, or add a new structure
        $lastCId = count($cleanPosition) - 1;
        if ($cleanPosition[$lastCId]["type"] != "one-columns") {
            array_push($cleanPosition, array(
                "type" => "one-columns",
                "entries" => array(0 => array())
            ));
            $lastCId = count($cleanPosition) - 1;
        }
        array_push($cleanPosition[$lastCId]["entries"][0], "footer");
    }

    return $cleanPosition;
}

/**
 * Clean a string, escaping any meta characters that could be
 * used to disrupt an SQL string. i.e. "'" => "\'" etc.
 *
 * @param String (or Array)
 * @return String (or Array) of cleaned data, suitable for use within an SQL
 *    statement.
 */
function escape_string($string, $link)
{
    $escaped_string = "";
    // if the string is actually an array, do a recursive cleaning.
    // Note, the array keys are not cleaned.
    if (is_array($string)) {
        $clean = array();
        foreach (array_keys($string) as $row) {
            $clean[$row] = escape_string($string[$row], $link);
        }
        return $clean;
    }
    if (get_magic_quotes_gpc()) {
        $string = stripslashes($string);
    }
    if (!is_numeric($string)) {
        $escaped_string = mysqli_real_escape_string($link, $string);
    } else {
        $escaped_string = $string;
    }
    return $escaped_string;
}

function delTree($dir) {
    if (!is_dir($dir)) {
        return false;
    }
    $files = array_diff(scandir($dir), array('.','..'));
    foreach ($files as $file) {
        (is_dir("$dir/$file")) ? delTree("$dir/$file") : unlink("$dir/$file");
    }
    return rmdir($dir);
}

function buildPagination($pageAmount, $page, $nav, $subnav, $sort, $sortDirection, $optionalId = "")
{
    $pagination = "";

    if ($pageAmount <= 7) {
        for ($i = 1; $i <= $pageAmount; $i++) {
            $cls = $page == $i ? "active" : "";
            $link = $page == $i ? "" : "href='/admin/" . $nav . "/" . $optionalId . $subnav . "/" . $sort . "/" . $sortDirection . "/" . $i . "'";
            $pagination .= "<li class='pagination " . $cls . "'><a data-page='" . $i . "' " . $link . ">" . $i . "</a></li>";
        }
    } else if ($pageAmount > 7) {
        // though case..
        $start = 1;
        $end = 7;
        if ($page > 4) {
            // stepping over the middle of the first "page"
            $start += ($page - 4);
            $end = $start + 6;
        }
        if ($end > $pageAmount) {
            $end = $pageAmount;
            $start = $end - 6;
        }
        for ($i = $start; $i <= $end; $i++) {
            $cls = $page == $i ? "active" : "";
            $link = $page == $i ? "" : "href='/admin/" . $nav . "/" . $optionalId . $subnav . "/" . $sort . "/" . $sortDirection . "/" . $i . "'";
            $pagination .= "<li class='pagination " . $cls . "'><a data-page='" . $i . "' " . $link . ">" . $i . "</a></li>";
        }
    }

    if ($pageAmount == 0) {
        $pagination .= "<li class='active'><a>1</a></li>";
    }
    return $pagination;
}

function addFiltersToQuery($filters, $query, $first)
{
    if (is_array($filters) && count($filters) > 0) {
        foreach ($filters as $key => $value) {
            $query .= $first ? "WHERE " : "AND ";
            $key = str_replace("filter-", "", $key);
            if (strpos($value, "*") !== false) {
                $value = str_replace("*", "%", $value);
            }

            // map problematic fields..
            $isTime = false;
            $isExact = false;
            $isNULL = false;
            if ($key == "product_start") {
                $key = "`p`.`start`";
                $isTime = true;
            }
            if ($key == "start" || $key == "timestamp" || $key == "sent") {
                $isTime = true;
            }
            if ($key == "used") {
                $isExact = true;
            }
            if ($key == "product_idfk") {
                $key = "`product_idfk`";
                $isExact = true;
            } else if ($key == "product_id") {
                $key = "`p`.`id`";
                $isExact = true;
            } else if ($key == "account_idfk") {
                $key = "`account_idfk`";
                $isExact = true;
            } else if ($key == "account_id") {
                $key = "`a`.`id`";
                $isExact = true;
            } else if ($key == "group_name") {
                $key = "`g`.`name`";
            } else if ($key == "upload_id") {
                $key = "`vu`.`upload_id`";
                $isExact = true;
            } else if ($key == "invite_start") {
                $key = "`im`.`start`";
                $isTime = true;
            } else if ($key == "created_timestamp") {
                $key = "`cc`.`created_timestamp`";
                $isTime = true;
            } else if ($key == "bill_start") {
                $key = "`b`.`start`";
                $isTime = true;
            } else if ($key == "bill_end") {
                $key = "`b`.`end`";
                $isTime = true;
            } else if ($key == "bill_state") {
                $key = "`b`.`state`";
            } else if ($key == "payout_state") {
                $key = "`pr`.`state`";
            } else if ($key == "email_state") {
                $key = "`e`.`state`";
            } else if ($key == "invite_id") {
                $key = "`im`.`id`";
                $isExact = true;
            } else if ($key == "invite_email") {
                $key = "`im`.`email`";
            } else if ($key == "invite_state") {
                $key = "`im`.`state`";
            } else if ($key == "coupon_id") {
                $key = "`cc`.`id`";
                $isExact = true;
            } else if ($key == "nid") {
                $key = "`n`.`id`";
            } else if ($key == "newsletter_title") {
                $key = "`n`.`title`";
            } else if ($key == "newsletter_start") {
                $isTime = true;
                $key = "`n`.`start`";
            } else if ($key == "mail_address") {
                $key = "address";
            } else if ($key == "subscriber_start") {
                $isTime = true;
                $key = "`s`.`start`";
            } else if ($key == "tenant_name") {
                $key = "`t`.`name`";
            } else if ($key == "tenant_id") {
                $key = "`a`.`tenant_idfk`";
            } else if ($key == "wedding_date") {
                $isTime = true;
                $key = "`s`.`wedding_date`";
            } else if ($key == "subscriber_email") {
                $key = "`s`.`email`";
            } else if ($key == "shop_category_idfk") {
                $key = "`shop_category_idfk`";
                $isExact = true;
            } else if ($key == "full_name") {
                $key = "CONCAT(`a`.`name`, ' ', `a`.`lastname`)";
            } else if ($key == "address") {
                $key = "CONCAT(`a`.`zip`, ' ', `a`.`city`)";
            } else if (strpos($key, "`") === false) {
                $key = "`" . $key . "`";
            }
            if ($value == "NULL") {
                $isNULL = true;
            }

            // type handling
            if ($isTime) {
                $date = new DateTime();
                if (strpos($value, " ") !== false) {
                    $parts = explode(" ", $value);
                    $value = $parts[0];
                }
                if (strpos($value, "/") !== false) {
                    $date = DateTime::createFromFormat('m/d/y', $value);
                    if ($date === false) {
                        $date = DateTime::createFromFormat('m/d/yy', $value);
                    }
                } else if (strpos($value, ".") !== false) {
                    $date = DateTime::createFromFormat('d.m.y', $value);
                    if ($date === false) {
                        $date = DateTime::createFromFormat('d.m.yy', $value);
                    }
                }

                $daysMorning = mktime(0, 0, 0, $date->format("m"), $date->format("d"), $date->format("y"));
                $daysEvening = mktime(23, 59, 59, $date->format("m"), $date->format("d"), $date->format("y"));

                $query .= $key . " >= FROM_UNIXTIME(" . ($daysMorning) . ") AND " . $key . " <= FROM_UNIXTIME(" . ($daysEvening) . ") ";
            } else if ($isExact && !$isNULL && is_numeric($value)) {
                $query .= "" . $key . " = " . $value . " ";
            } else if ($isNULL) {
                $query .= "" . $key . " IS NULL ";
            } else if (is_numeric($value)) {
                $query .= "" . $key . " = " . $value . " ";
            } else {
                $query .= "" . $key . " LIKE '" . $value . "%' ";
            }

            $first = false;
        }
    }

    return $query;
}