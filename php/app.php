<?
require("utilities/utils.php");
require("utilities/slim/Slim.php");

class App
{
    var $version = "0.0.1";

    var $second = 1;
    var $minute = null;
    var $hour = null;
    var $day = null;
    var $week = null;
    var $month = null;

    var $includes = array();
    var $navigate = null;
    var $isRESTRequest = false;
    var $isScriptRequest = false;
    var $jsonResult = null;
    var $scriptResult = null;
    var $scriptContentType = null;

    var $metaData = null;

    /**
     * Constructor
     * must be called before the headers are sent! (first lines of code in your index.php)
     */
    public function App($isDev = null, $isCliCall = false)
    {
        $this->minute = $this->second * 60;
        $this->hour = $this->minute * 60;
        $this->day = $this->hour * 24;
        $this->week = $this->day * 7;
        $this->month = $this->day * (30.416666666666667); // 365 / 12 = 30.416666667, 0.416 days = 9.984 hours

        // someday we could remove that and fix all this annoying "undefined index" stuff in array-access... or maybe never :D
        error_reporting(E_ALL ^ E_NOTICE);

        // start a new session
        try {
            session_start();
        } catch (Exception $e) {
            // doesn't matter, probably phpunit tests
        }

        $this->slim = new Slim(array(
            'log.enable' => true,
            'log.path' => './logs',
            'log.level' => 4
        ));

        $this->initSlimRoutes();

        $dir = $this->getSelfDir(true) . "/data/";
        $path = $dir . "meta.json";
        $string = file_get_contents($path);
        $this->metaData = json_decode($string, true);
    }

    /*
     ================================
     Slim initialization
     ================================
     */

    /**
     * Init Routes for Slim-Framework
     */
    public function initSlimRoutes()
    {
        //GET route
        $this->slim->get('/', to_closure('fallback', $this));
        $this->slim->get('/:navigate', to_closure('handleNavigate', $this));


        //POST route
        $this->slim->post('/load/cards', to_closure('loadCards', $this));

        $app = $this->slim;
        $app->notFound(function () use ($app) {
//            header("Location: /404");
            exit;
        });

        $this->slim->run();
    }

    /*
    ================================
    All Slim GET functions
    ================================
    */

    /**
     * When user is entering domain without any "navigation" after the /
     */
    public function fallback()
    {
        header("Location: /home");
        exit;
    }

    /**
     * When someone is entering a single word after the / -> a navigation
     *
     * @param $navigate
     */
    public function handleNavigate($navigate)
    {
        $this->navigate = $navigate;
        array_push($this->includes, "templates/home.php");
    }

    public function getServerSide() {
        $result = array(
            "someNiceData" => 13
        );

        return json_encode($result);
    }

    /*
     ================================
     Basic page rendering
     ================================
     */

    /**
     * @return mixed|string
     */
    public function getPageTitle()
    {
        return "Achievement Unlocked";
    }

    /**
     * @return string
     */
    public function getHtmlClass()
    {
        $cls = "";

        return $cls;
    }

    /**
     * @return string
     */
    public function getBodyClass()
    {
        $cls = "";

        return $cls;
    }

    /**
     * @public
     * @param $app
     */
    /**
     * @public
     * @param $app
     */
    public function render($app)
    {
        if ($this->isRESTRequest) {
            return;
        }

        foreach ($this->includes as $key => $value) {
            include($this->getSelfDir()."/".$value);
        }
    }

    /**
     * @param $app
     */
    /**
     * @param $app
     */
    public function renderAjax($app)
    {
        $app = $this;
        $navigate = $this->navigate;
        ob_start(); // start output buffering -> nothing will be sent to the client

        // include normal content-php so we are running through the normal code of the current navigate
        foreach ($this->includes as $key => $value) {
            include($value);
        }

        $html = ob_get_contents(); // get the output as a string
        ob_end_clean(); // silently close

        // first two lines prevent the browser from caching the response, third the MIME type for JSON
        header('Cache-Control: no-cache, must-revalidate');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        $browser = getUserBrowser();
        $this->jsonResult["detectedBrowser"] = $browser;
        if ($browser == "ie") {
            // IE wants to start a download with the correct Content-Type (application/json) *facepalm*
            // .. and with the not-so-false "text/plain", he will add a <pre> tag at the beginning of the json-string *tripple-facepalm* - and of course, without the closing tag.. if IE fails, it fails 200%!
            header('Content-type: text/html');
        } else {
            header('Content-type: application/json');
        }
        echo json_encode($this->jsonResult);
    }

    public function renderSlim()
    {
        // first two lines prevent the browser from caching the response, third the MIME type for JSON
        header('Cache-Control: no-cache, must-revalidate');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        $browser = getUserBrowser();

        if ($browser == "ie") {
            // IE wants to start a download with the correct Content-Type (application/json) *facepalm*
            // .. and with the not-so-false "text/plain", he will add a <pre> tag at the beginning of the json-string *tripple-facepalm* - and of course, without the closing tag.. if IE fails, it fails 200%!
            header('Content-type: text/html');
        } else {
            header('Content-type: application/json');
        }

        echo json_encode($this->jsonResult);
    }

    public function renderScript()
    {
        // first two lines prevent the browser from caching the response, third the MIME type for JSON
        header('Content-type: ' . $this->scriptContentType);
        echo $this->scriptResult;
    }

    /*
     ================================
     Additional Helper Functions
     ================================
     */


    /**
     * @param bool $root
     * @return mixed|string
     */
    public function getSelfDir($root = false)
    {
        $dir = dirname(__FILE__);
        if ($this->isDevEnvironment) {
            $isLocal = strpos($dir, "opt/local") !== false || strpos($dir, "mojito-website") !== false;
            if ($isLocal) {
                return $root ? "/opt/local/apache2/htdocs" : "/opt/local/apache2/htdocs/php";
            } else {
                return $root ? "/var/www/vhosts/vp24.com/dev.vp24.com" : "/var/www/vhosts/vp24.com/dev.vp24.com/php";
            }
        }

        $parts = explode("/", $dir);
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

        if (strpos($path, "php") == false && !$root) {
            $path = $path . "/php";
        }
        if (strpos($path, "php") !== false && $root) {
            $path = str_replace("/php", "", $path);
            $path = str_replace("\php", "", $path);
        }

        if (strpos($path, "\\") !== false) {#colorpickr .control-group.slider-container .ui-widget-content
            // windows
            $path = str_replace("/", "\\", $path);
            return $path;
        } else {
            return "/" . $path;
        }
    }

    public function hasLess()
    {
        return false;
    }

    /**
     * @param $message
     * @return string
     */
    public function makeHtmlMail($message)
    {
        ob_start(); // start output buffering -> nothing will be sent to the client
        $app = $this; // we need this variable in our includes..
        include($this->getSelfDir() . "/templates/html-mail.php");
        $content = ob_get_contents(); // get the output as a string
        ob_end_clean(); // silently close

        $content = resetCssInline($content);

        return $content;
    }

    /**
     * @param $subject
     * @param $message
     */
    public function errorMail($subject, $message)
    {
        if ($this->mail(array(0 => array("mail" => "remo.vetere@gmail.com")), $subject, $message)) {
            // its not so bad if this fails sometimes.. at least the entry in the database is successfully!
        } else {
            error_log("Could not send error-mail!");
        }
    }

    /**
     * @param $toAddresses
     * @param $subject
     * @param $messageHtml
     * @param string $replyMail
     * @param string $replyName
     * @param null $attachment
     * @return bool
     */
    public function mail($toAddresses, $subject, $messageHtml, $replyMail = "", $replyName = "", $attachment = null)
    {
        if (strlen($replyMail) == 0) {
            $replyMail = $this->getTenantTxt("project.mail");
        }
        if (strlen($replyName) == 0) {
            $replyName = $this->getTenantTxt("project.name");
        }

        $success = false;
        try {
            $mail = new PHPMailer();
            $mail->SetLanguage("de", "/php/langs/phpmailer");
            $mail->CharSet = 'utf-8';

            if (is_array($toAddresses)) {
                foreach ($toAddresses as $value) {
                    $mail->AddAddress($value["mail"], $value["name"]);
                }
            } else {
                $mail->AddAddress($toAddresses);
            }
            $mail->AddReplyTo($replyMail, $replyName);

            $mail->From = $replyMail;
            $mail->FromName = $replyName;

            if (isset($attachment)) {
                if (is_array($attachment)) {
                    foreach ($attachment as $idx => $a) {
                        $mail->AddAttachment($a);
                    }
                } else {
                    $mail->AddAttachment($attachment);
                }
            }

//            $mail->WordWrap = 50;                               // set word wrap to 50 characters
            $mail->IsHTML(true); // set email format to HTML

            $mail->Subject = $subject;
            $mail->Body = $messageHtml;
            // trying to avoid that email-clients will insert random-line-breaks that can fuck up the whole content -.-
            $mail->Body = str_replace("<", "\r\n<", $mail->Body);
            // thats so stupid.. if we make a full-path inside an image of email-html, it is broken here.. an explizit search-replace will fix...
            $mail->Body = str_replace("/img/", "https://" . $this->baseUrl . "/img/", $mail->Body);
            $mail->Body = str_replace("/userfiles/", "https://" . $this->baseUrl . "/userfiles/", $mail->Body);

            $md = new Markdownify_Extra(MDFY_LINKS_EACH_PARAGRAPH, MDFY_BODYWIDTH, false);
            $alt = $md->parseString($messageHtml);
            $mail->AltBody = $alt;

            $success = $mail->Send();
        } catch (phpmailerException $e) {
            error_log("app.mail() -> " . $e->errorMessage()); //Pretty error messages from PHPMailer
        } catch (Exception $e) {
            error_log("app.mail() -> " . $e->getMessage()); //Boring error messages from anything else!
        }

        return $success;
    }

    public function getPopover($pop) {
        ob_start(); // start output buffering -> nothing will be sent to the client

        $app = $this;
        include($this->getSelfDir()."/popover/".$pop);

        $html = ob_get_contents(); // get the output as a string
        ob_end_clean(); // silently close

        return str_replace('"', "'", $html);
    }


    public function getPopoverAchiv($pop, $user) {
        ob_start(); // start output buffering -> nothing will be sent to the client

        $app = $this;
        include($this->getSelfDir()."/popover/".$pop);

        $html = ob_get_contents(); // get the output as a string
        ob_end_clean(); // silently close

        return str_replace('"', "'", $html);
    }

    public function loadCards() {
        $this->isRESTRequest = true;

        ob_start(); // start output buffering -> nothing will be sent to the client

        $app = $this;
        $dir = $this->getSelfDir(true) . "/data/";
        $path = $dir . "meta.json";
        $string = file_get_contents($path);
        $app->metaData = json_decode($string, true);

        foreach ($_POST["users"] as $user) {
            $this->metaData["data"][$user];
            include($this->getSelfDir()."/templates/card.php");
        }

        $html = ob_get_contents(); // get the output as a string
        ob_end_clean(); // silently close

        $this->jsonResult = array(
            "html" => $html
        );
    }

    public function isHallOfFameActive($type) {
        $hasIt = false;
        foreach ($this->metaData["hall_of_fame"] as $key => $data) {
            if ($key == $type) {
                foreach (is_array($data) ? $data : array() as $idx => $leDate) {
                    foreach ($leDate as $name => $unixTstamp) {
                        $hasIt = true;
                        break;
                    }
                }

            }
        }

        return $hasIt ? "is-active" : "";
    }

    public function isAchievementActive($type, $user) {
        $hasIt = false;
        $data = $this->metaData[$user];

        foreach (isset($data["awards"]) ? $data["awards"] : array() as $key => $leDate) {
            if ($key == $type) {
                $hasIt = true;
                break;
            }
        }

        return $hasIt ? "isActive" : "";
    }

    public function getFirstDate($leDate) {
        $result = $leDate["1"];
        if (!isset($result)) {
            foreach ($leDate as $key => $value) {
                $result = $value;
                break;
            }
        }

        return $result;
    }

    public function parseDate($leDate, $list) {
        if (is_array($leDate)) {
            foreach ($leDate as $leEntry) {
                $date = new DateTime();
                $date->setTimestamp(strtotime($leEntry));
                $list .= "<tr><td>Received on</td><td>".date_format($date, "d.m.y")."</td></tr>";
            }
        } else {
            $date = new DateTime();
            $date->setTimestamp(strtotime($leDate));
            $list .= "<tr><td>Received on</td><td>".date_format($date, "d.m.y")."</td></tr>";
        }

        return $list;
    }

}
